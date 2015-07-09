<?php

/**
 * Sections Model
 *
 * @package BluApplication
 * @subpackage BluModels
 */
class WorkitmomSectionsModel extends BluModel
{
	/**
	 * Array of all sections, indexed by section id
	 *
	 * @var array
	 */
	private $_sections;

	/**
	 * Array of top-level sections
	 *
	 * @var array
	 */
	private $_topSections;

	/**
	 * Array of section links
	 *
	 * @var array
	 */
	private $_sectionLinks;

	/**
	 * Current section details
	 *
	 * @var array
	 */
	private $_currentSection;

	/**
	 * Section model constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Build section hierarchy
		$this->_getSectionHierarchy();
	}

	/**
	 * Get section hierarchy
	 */
	private function _getSectionHierarchy()
	{
		// Get language details
		$language = BluApplication::getLanguage();
		$langCode = $language->getLanguageCode();

		// Get hierarchy from cache/build
		$hierarchyCache = $this->_cache->get('sectionHierarchy_'.$langCode);
		if ($hierarchyCache === false) {
			$this->_buildSectionHierarchy();
			$hierarchyCache = array(
				'sections' => $this->_sections,
				'sectionLinks' => $this->_sectionLinks,
				'topSections' => $this->_topSections);
			$this->_cache->set('sectionHierarchy_'.$langCode, $hierarchyCache);
		} else {
			$this->_sections = $hierarchyCache['sections'];
			$this->_sectionLinks = $hierarchyCache['sectionLinks'];
			$this->_topSections = $hierarchyCache['topSections'];
		}
	}

	/**
	 * Build section hierarchy
	 */
	private function _buildSectionHierarchy($topSectionId = 0, $topSectionLink = '')
	{
		// Get language details
		$language = BluApplication::getLanguage();
		$langCode = $language->getLanguageCode();
		$defaultLang = BluApplication::getSetting('defaultLang');

		// Load child sections from cache/db
		$sections = $this->_cache->get('sections_'.$topSectionId.'_'.$langCode);
		if ($sections === false) {
			$query = 'SELECT s.sectionID, s.topSection, ls.name as sectionName,
					ls.description, ls.keywords,
					s.sectionslug, s.sectionImage, s.rootSection
				FROM sections AS s
				LEFT JOIN languageSections AS ls ON ls.id = s.sectionID
				WHERE s.topSection = '.(int)$topSectionId.'
					AND s.sectionDisabled = 0
				ORDER BY s.sectionOrder, field(ls.lang, "'.$defaultLang.'", "'.$langCode.'")';
			$this->_db->setQuery($query);
			$sections = $this->_db->loadAssocList('sectionID');

			// Clean and trim descriptions
			foreach($sections as &$section) {
			    $section['miniDescription'] = Text::trim($section['description'], 100, false);
			    $section['fullDescription'] = Text::cleanHTML($section['description']);
				$section['sectionImages'] = $this->getSectionImages($section['sectionID'], $langCode);
				$section['sectionImage'] = urlencode($section['sectionImage']);
			}

			// Store in cache
			$this->_cache->set('sections_'.$topSectionId.'_'.$langCode, $sections);
		}

		foreach($sections as &$section) {

			// Default to inactive, with no sub-sections and add link
			$section['active'] = false;
			$section['subSections'] = null;
			$section['link'] = ($topSectionLink ? $topSectionLink.'/' : '').$section['sectionslug'];

			// Add to section indexing arrays (for fast lookups)
			$this->_sections[$section['sectionID']] = &$section;
			$this->_sectionLinks[$section['link']] = &$section;

			// Append to parents sub section list
			if ($section['topSection'] > 0) {
				$this->_sections[$section['topSection']]['subSections'][$section['sectionID']] = &$section;
			} else {
				$this->_topSections[] = &$section;
			}

			// Build child hierarchy
			$this->_buildSectionHierarchy($section['sectionID'], $section['link']);
		}
	}

	/**
	 * Get all sections for an image
	 *
	 * @param int Section id
	 * @param string Language code
	 * @return array Section images
	 */
	private function getSectionImages($sectionId, $langCode)
	{
		$defaultLang = BluApplication::getSetting('defaultLang');

		$query = 'SELECT * FROM
				sectionHeaderImage
			WHERE sectionId = '.(int) $sectionId.'
			ORDER BY sequence ASC, field(lang, "'.$defaultLang.'", "'.$langCode.'")';
		$this->_db->setQuery($query);
		$sectionImages = $this->_db->loadAssocList('sequence');

		if (empty($sectionImages)) {
			return false;
		}

		// Url encode image names
		foreach ($sectionImages as &$sectionImage) {
			$sectionImage['fileName'] = urlencode($sectionImage['fileName']);
		}

		return $sectionImages;
	}

	/**
	 * Get all section details
	 *
	 * @return array Complete section hierarchy
	 */
	public function getAllSections()
	{
		return $this->_topSections;
	}

	/**
	 * Get section details by id
	 *
	 * @param int Section id
	 * @return array Section details
	 */
	public function getSection($id)
	{
		if (isset($this->_sections[$id])) {
			$section = $this->_sections[$id];
		} else {
			$section = false;
		}
		return $section;
	}

	/**
	 * Get section link
	 *
	 * @param int Section id
	 * @return string Section link
	 */
	public function getSectionLink($id)
	{
		$section = $this->getSection($id);
		return $section ? $section['link'] : false;
	}

	/**
	 * Get section details by link
	 *
	 * @param mixed Section link string or array
	 * @return array Section details
	 */
	public function getSectionFromLink($link)
	{
		// Implode array if required
		if (is_array($link)) {
			$link = implode('/', $link);
		}

		// Find section in links mapping
		if (isset($this->_sectionLinks[$link])) {
			$section = $this->_sectionLinks[$link];
		} else {
			$section = false;
		}

		return $section;
	}

	/**
	 * Get list of featured sections and associated items
	 *
	 * @return array Array of sections and either product or sub-section items
	 */
	public function getFeaturedSections()
	{
		$langObj = BluApplication::getLanguage();
		$langCode = $langObj->getLanguageCode();
		$defaultLang = BluApplication::getSetting('defaultLang');
		// Get section IDs from cache/database
		$sections = $this->_cache->get('featuredSections');
		if ($sections === false) {
			$query = 'SELECT s.sectionID, ls.name AS sectionName, s.sectionslug, s.sectionImage, s.rootSection
				FROM sections AS s
				LEFT JOIN languageSections AS ls ON ls.id=s.sectionID
				WHERE cFeat = 1
				ORDER BY s.sectionOrder, field(ls.lang, "'.$defaultLang.'", "'.$langCode.'")';
			$this->_db->setQuery($query);
			$sections = $this->_db->loadAssocList('sectionID');

			// Store in cache
			$this->_cache->set('featuredSections', $sections);
		}

		// Load sub-sections/products
		foreach($sections as &$section) {
			if ($section['rootSection'] == 1) {

				// Load 5 random products
				$section['itemsType'] = 1;
				$productsModel = BluApplication::getModel('products', 'rand', 0, 5);
				$section['items'] = $productsModel->getProducts($section['sectionID']);
			} else {

				// Get first 5 sub-sections
				$section['itemsType'] = 2;
				$subSections = $this->_sections[$section['sectionID']]['subSections'];
				if (!empty($subSections)) {
					$section['items'] = array_slice($subSections, 0, 5, true);
				} else {
					$section['items'] = null;
				}
			}
		}
		return $sections;
	}

	/**
	 * Get list of all nested sub-section ids
	 *
	 * @param int Section id
	 * @return array Array of sub-section ids
	 */
	public function getSubSectionIdList($id)
	{
		// Use top level sections as starting point?
		if ($id === 0) {
			$subSections = &$this->_topSections;
		} else {
			$section = $this->getSection($id);
			$subSections = &$section['subSections'];
		}
		if ($subSections) {
			foreach($subSections as &$subSection) {
				$sectionIds[] = $subSection['sectionID'];
				if ($subSectionIds = $this->getSubSectionIdList($subSection['sectionID'])) {
					$sectionIds = array_merge($sectionIds, $subSectionIds);
				}
			}
		} else {
			$sectionIds = null;
		}
		return $sectionIds;
	}

	/**
	 * Sets the current section
	 *
	 * @param int Section ID
	 */
	public function setCurrentSection($id)
	{
		$this->_currentSection =& $this->_sections[$id];
		$this->_markSectionActive($id);
	}

	/**
	 * Gets the current section
	 *
	 * @return int Section details
	 */
	public function getCurrentSection()
	{
		return $this->_currentSection;
	}

	/**
	 * Marks the given section and all parents as active
	 *
	 * @param int Section ID
	 */
	private function _markSectionActive($id)
	{
		if (!$id || !array_key_exists($id, $this->_sections)) {
			return false;
		}

		// Mark section, and all parents active
		$this->_sections[$id]['active'] = true;
		if ($topSection = $this->_sections[$id]['topSection']) {
			$this->_markSectionActive($topSection);
		}
	}
}
?>