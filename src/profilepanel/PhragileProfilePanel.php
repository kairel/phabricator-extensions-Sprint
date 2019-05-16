<?php

final class PhragileProfilePanel
    extends PhabricatorProfilePanel {

  const PANELKEY = 'project.phragile';

  public function getPanelTypeName() {
    return pht('Phragile');
  }

  private function getDefaultName() {
    return pht('Phragile');
  }

  public function shouldEnableForObject($object) {
    $enable_phragile = PhabricatorEnv::getEnvConfig('sprint.enable-phragile');

    if ($enable_phragile) {
      return true;
    }
    return false;
  }

  public function getDisplayName(
      PhabricatorProfilePanelConfiguration $config) {
    $name = $config->getPanelProperty('name');

    if (strlen($name)) {
      return $name;
    }

    return $this->getDefaultName();
  }

  public function buildEditEngineFields(
      PhabricatorProfilePanelConfiguration $config) {
    return array(
        id(new PhabricatorTextEditField())
            ->setKey('name')
            ->setLabel(pht('Name'))
            ->setPlaceholder($this->getDefaultName())
            ->setValue($config->getPanelProperty('name')),
    );
  }

  protected function newMenuItemViewList(
      PhabricatorProfilePanelConfiguration $config) {

    $project = $config->getProfileObject();

    $has_children = ($project->getHasSubprojects()) ||
        ($project->getHasMilestones());

    $id = $project->getID();

    $name = $this->getDisplayName($config);
    $icon = 'fa-link';
    $phragile_base_uri = PhabricatorEnv::getEnvConfig('sprint.phragile-uri');
    $uri = $phragile_base_uri.$id;

    $item = $this->newItemView()
        ->setURI($uri)
        ->setName($name)
        ->setDisabled(!$has_children)
        ->setIcon($icon);

    return array(
        $item,
    );
  }

}
