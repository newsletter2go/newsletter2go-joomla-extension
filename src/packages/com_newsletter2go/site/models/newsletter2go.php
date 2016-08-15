<?php

jimport('joomla.application.component.model');

class Newsletter2GoModelNewsletter2Go extends JModelList
{

    /**
     * Fetches option from database and json_decodes it if flag $decode is true
     *
     * @param string $name
     * @param string $default
     * @param bool|false $decode
     * @return mixed
     */
    public function getOption($name, $default = '', $decode = false)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`value`');
        $query->from('#__newsletter2go');
        $query->where("`name` = '$name'");
        $db->setQuery((string)$query);

        $object = $db->loadObject();
        $value = $object ? $object->value : $default;

        return $decode === true ? json_decode($value, true) : $value;
    }

    public function setOption($name, $value)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__newsletter2go')->where("`name` = '$name'");
        $db->setQuery($query);
        $db->execute();

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $values = array($db->quote($name), $db->quote($value));
        $columns = array('name', 'value');
        $query->insert($db->quoteName('#__newsletter2go'))
            ->columns($columns)
            ->values(implode(',', $values));
        $db->setQuery($query);
        $db->execute();
    }
    
    public function getExtensionVersion()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`manifest_cache`');
        $query->from('#__extensions');
        $query->where("`element` = 'pkg_newsletter2go' AND type = 'package'");
        $db->setQuery((string)$query);
        
        return $db->loadObject();
    }
    
    public function getPost($id, $lang)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("c.id as id,
                c.title as title, 
                c.alias as link, 
                c.introtext as shortDescription, 
                c.fulltext as description, 
                c.created as date,
                c.images as images, 
                cat.title as category, 
                u.name as author 
                ");
        
        $query->from('#__content c')
                ->leftJoin('#__users u ON u.id = c.created_by')
                ->leftJoin('#__categories cat ON cat.id = c.catid');
        $query->where("c.id = '$id' AND c.language = '$lang'");
        $db->setQuery((string)$query);
        $post = $db->loadObject();
        
        if (!$post) {
            return null;
        }
        
        $post->category = array($post->category);
        $post->url = JURI::root();
        $post->link = 'index.php/' .$id . '-' . $post->link;
        $post->description = $post->shortDescription . $post->description;
        
        //getting images
        $images = array();
        $post->images = json_decode($post->images);
        if ($post->images->image_intro) {
            $images[] = JURI::root() . $post->images->image_intro;
        }
        
        if ($post->images->image_fulltext) {
            $images[] = JURI::root() . $post->images->image_fulltext;
        }
        
        $htmlDoc = new DOMDocument();
        $htmlDoc->loadHTML($post->description);
        $htmlImages = $htmlDoc->getElementsByTagName('img');
        foreach ($htmlImages as $image) {
            $images[] = JURI::root() . $image->attributes->getNamedItem("src")->value;
        }
        
        $post->images = $images;
        
        //getting tags
        $post->tags = array();
        $query = $db->getQuery(true);
        $query->select('t.alias as name')
                ->from('#__contentitem_tag_map ctm')
                ->leftJoin('#__tags t ON t.id = ctm.tag_id')
                ->where("ctm.content_item_id = $id");
        $db->setQuery((string)$query);
        $tags = $db->loadObjectList();
        foreach ($tags as $tag) {
            $post->tags[] = str_replace('-', ' ', $tag->name);
        }
        
        return $post;
    }

    public function getLanguages()
    {
        $result = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__languages');

        $db->setQuery($query);
        $languages = $db->loadObjectList();

        foreach ($languages as $lang) {
            $result[] = array(
                'title' => $lang->title,
                'lang_code' => $lang->lang_code,
                'description' => $lang->description,
                'default' => 'false',
            );
        }

        return $result;
    }

}