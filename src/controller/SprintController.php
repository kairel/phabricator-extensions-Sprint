<?php

/**
 * @author Michael Peters
 * @author Christopher Johnson
 * @license GPL version 3
 */

abstract class SprintController extends PhabricatorController {

  public function shouldAllowPublic() {
    return true;
   }

  public function getProjectsURI() {
    return '/project/';
  }

  public function getUser() {
    return $this->getRequest()->getUser();
  }

  public function setApplicationURI() {
    return new PhutilURI($this->getApplicationURI());
  }

  public function buildApplicationMenu() {
      return $this->buildSprintNavView($this->getUser(),
          $this->setApplicationURI(), true)->getMenu();
  }

  public function buildNavMenu() {
    $nav = id(new AphrontSideNavFilterView())
        ->setBaseURI(new PhutilURI($this->getApplicationURI().'report/'))
        ->addLabel(pht('Sprint Projects'))
        ->addFilter('list', pht('List'))
        ->addLabel(pht('Open Tasks'))
        ->addFilter('project', pht('By Project'))
        ->addFilter('user', pht('By User'))
        ->addLabel(pht('Burn Up'))
        ->addFilter('burn', pht('Burn Up Rate'))
        ->addFilter('history', pht('Task Project History'));
    return $nav;
  }

  /**
   * @param PhutilURI $uri
   */
  public function buildSprintNavView($viewer, $uri, $for_app = false) {
    $request = $this->getRequest();
    $id = $request->getURIData('id');
    $slug = $request->getURIData('slug');
    if ($slug) {
      $id = $this->getProjectIDfromSlug($slug, $viewer);
    }
    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI($uri);

    if ($for_app) {
      if ($id) {
        $nav->addFilter("profile/{$id}/", pht('Profile'));
        $nav->addFilter("board/{$id}/", pht('Workboard'));
        $nav->addFilter("members/{$id}/", pht('Members'));
        $nav->addFilter("feed/{$id}/", pht('Feed'));
        $nav->addFilter("details/{$id}/", pht('Edit Details'));
      }
      $nav->addFilter('create', pht('Create Project'));
    }

    id(new PhabricatorProjectSearchEngine())
        ->setViewer($viewer)
        ->addNavigationItems($nav->getMenu());

    if ($viewer->isLoggedIn()) {
      $nav->addLabel(pht('Sprints'));
      $nav->addFilter('report/list', pht('Sprint List'));
    }

    $nav->selectFilter(null);

    return $nav;
  }

   protected function buildSprintApplicationCrumbs($can_create) {
    $crumbs = $this->buildCrumbs('fa-bar-chart', $this->getApplicationURI());

    $crumbs->addAction(
        id(new PHUIListItemView())
            ->setName(pht('Create Sprint'))
            ->setHref('/conduit/method/sprint.create/')
            ->setIcon('fa-calendar')
            ->setDisabled(!$can_create));

    return $crumbs;
  }

  protected function buildCrumbs($sprite, $uri) {
    $crumbs = array();

      $crumbs[] = id(new PHUICrumbView())
          ->setHref($uri)
          ->setAural($sprite)
          ->setIcon($sprite);

    $view = new PHUICrumbsView();
    foreach ($crumbs as $crumb) {
      $view->addCrumb($crumb);
    }

    return $view;
  }

  public function getProjectIDfromSlug($slug, $viewer) {
    $project = id(new PhabricatorProjectQuery())
        ->setViewer($viewer)
        ->withSlugs(array($slug))
        ->executeOne();
    $id = $project->getID();
    return $id;
  }

  protected function isSprint($object) {
    $validator = new SprintValidator();
    $issprint = call_user_func(array($validator, 'checkForSprint'),
        array($validator, 'isSprint'), $object->getPHID());
    return $issprint;
  }

  public function getErrorBox($e) {
    $error_box = id(new PHUIInfoView())
        ->setTitle(pht('Sprint could not be rendered for this project'))
        ->setErrors(array($e->getMessage()));
    return $error_box;
  }
}
