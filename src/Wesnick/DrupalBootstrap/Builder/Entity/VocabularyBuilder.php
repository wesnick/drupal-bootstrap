<?php

namespace Wesnick\DrupalBootstrap\Builder\Entity;


/**
 * Class VocabularyBuilder
 * 
 * @author Wesley O. Nichols <wesley.o.nichols@gmail.com>
 */
class VocabularyBuilder extends AbstractEntityTypeBuilder
{

    public function getEntityType()
    {
        return 'vocabulary';
    }

    public static function getTypes()
    {
        return taxonomy_vocabulary_get_names();
    }

    public function build()
    {
        if ($voc = taxonomy_vocabulary_machine_name_load($this->bundle)) {
            $voc->name = $this->label;
            $voc->description = $this->description;

        } else {
            $voc = (object) array(
                'machine_name' => $this->bundle,
                'name' => $this->label,
                'description' => $this->description,
            );
        }

        taxonomy_vocabulary_save($voc);
    }


} 
