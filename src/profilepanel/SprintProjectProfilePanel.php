<?php

final class SprintProjectProfilePanel
    extends PhabricatorProfilePanel {

  const PANELKEY = 'project.sprint';

  public function getPanelTypeName() {
    return pht('Project Burndown');
  }

  private function getDefaultName() {
    return pht('Burndown');
  }

  public function shouldEnableForObject($object) {
    return true;
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
    $icon = 'fa-calendar';
    $uri = "/project/sprint/view/{$id}/";

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
