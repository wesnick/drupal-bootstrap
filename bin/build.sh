#!/bin/bash
PWD="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
CWD=`pwd`
cd "$PDW"
drush dl drupal -y --drupal-project-rename=drupal
cd drupal
drush site-install -y standard --db-url=mysql://db:dbpass@localhost/drupal-bootstrap --site-name="Bootrapper Test"
drush dl entity ctools entityreference link email date field_collection field_group file_entity bundle_copy
drush en -y entity ctools entityreference link email date field_collection field_group file_entity bundle_copy
cd "$CWD"
