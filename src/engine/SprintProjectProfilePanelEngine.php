<?php

final class SprintProjectProfilePanelEngine
    extends PhabricatorProfileMenuEngine  {

  protected function isMenuEngineConfigurable() {
    return true;
  }

  public function getItemURI($path) {
    $project = $this->getProfileObject();
    $id = $project->getID();
    return "/project/{$id}/item/{$path}";
  }

  protected function getBuiltinProfileItems($object) {
    $items = array();

    $items[] = $this->newItemView()
        ->setBuiltinKey(PhabricatorProject::ITEM_PROFILE)
        ->setMenuItemKey(PhabricatorProjectDetailsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItemView()
        ->setBuiltinKey(PhabricatorProject::ITEM_POINTS)
        ->setMenuItemKey(PhabricatorProjectPointsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItemView()
        ->setBuiltinKey(PhabricatorProject::ITEM_WORKBOARD)
        ->setMenuItemKey(PhabricatorProjectWorkboardProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItemView()
        ->setBuiltinKey(PhabricatorProject::ITEM_MEMBERS)
        ->setMenuItemKey(PhabricatorProjectMembersProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItemView()
        ->setBuiltinKey(PhabricatorProject::ITEM_SUBPROJECTS)
        ->setMenuItemKey(
            PhabricatorProjectSubprojectsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItemView()
        ->setBuiltinKey(PhabricatorProject::ITEM_MANAGE)
        ->setMenuItemKey(PhabricatorProjectManageProfileMenuItem::MENUITEMKEY);

    return $items;
  }
}
