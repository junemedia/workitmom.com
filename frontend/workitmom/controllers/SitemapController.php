<?php

/**
 * Sitemap Controller
 *
 * @package BluApplication
 * @subpackage FrontendControllers
 */
class WorkitmomSitemapController extends ClientFrontendController
{
	/**
	 * Display sitemap
	 */
	public function view()
	{
		// Get all sections and their products
		$sectionsModel = $this->getModel('sections');
		$sections = $sectionsModel->getAllSections();
		$this->_addSectionProducts($sections);

		// Machine-readable xml
		$format = $this->_doc->getFormat();
		if ($format == 'xml') {
			include(BLUPATH_TEMPLATES.'/machine/sitemap.php');

		// Human-readable
		} else {
			$this->_doc->setTitle(Text::get('links_sitemap'));
			include(BLUPATH_TEMPLATES.'/products/sitemap.php');
		}
	}

	/**
	 * Add products to sections
	 */
	private function _addSectionProducts(&$sections)
	{
		$productsModel = $this->getModel('products');
		foreach($sections as &$section) {
			if ($section['rootSection'] == 1) {
				$section['products'] = $productsModel->getProducts($section['sectionID'], 'name_asc');
			} else {
				$section['products'] = false;
			}
			if ($section['subSections']) {
				$this->_addSectionProducts($section['subSections']);
			}
		}
	}

}
?>
