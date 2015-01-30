<?php

namespace Kajja\Tags;

use \Anax\DI\IInjectionaware,
    \Anax\DI\TInjectable,
    \Kajja\Tags\Tag as Tag;


/**
 * Controller class for Tags
 *
 */
class TagsController implements IInjectionaware 
{
    use TInjectable;

    /**
     * Initialize the controller
     *
     */
    public function initialize()
    {
        // Create Tag-model object
        $this->tagModel = new Tag();
        $this->tagModel->setDI($this->di);
    }

    /**
     * Gets all tags in database
     *
     * @return array multi dim containing two arrays with
     * tag names and tag ids respectively
     */
    public function allAction()
    {
        // Use the model to get all stored tags
        $allTags = $this->tagModel->findAll();

        // Creates an array with the tag info
        $tagInfo = [];
        foreach ($allTags as $tag) {
            $tagInfo['names'][] = $tag->name;
            $tagInfo['ids'][] = $tag->id;
        }
        return $tagInfo;
    }

    /**
     * Retrieve all tags and show in view
     *
     */
    public function listAction($questionId = null)
    {
        // Use the model to get all stored users
        $tags = $this->tagModel->findAll();

        $this->theme->setTitle('Taggar');
        $this->theme->setVariable('bodyClasses', 'page-container');
        $this->views->addString('<h3>Taggar</h3><hr>');
        $this->display($tags);
    }

    /**
     * Only the tags related to the question
     *
     * @param int the id of the question
     */
    public function filterAction($questionId)
    {
        $tags = $this->tagModel->questionTags($questionId); 
        $this->display($tags);
    }

    /**
     *  Display the tags in the tag view
     *
     */
    public function display($tags, $area = 'main')
    {
        // Add tags to view
        foreach ($tags as $tag) {
            $tagProp = $tag->getProperties();
            $this->views->add('tag/tag', [
                'tagId'     => $tagProp['id'],
                'tagName'   => $tagProp['name']
            ], $area);
        }
    }

    /**
     * Displays the most popular tags
     *
     */
    public function popularTagsAction($numOf, $area)
    {
        $tags = $this->tagModel->popTags($numOf);

        //Display the tags
        $this->display($tags, $area);
    }

}