<?php
/**
 * Controller class containing all the Dashboard related functionalities.
 *
 * @since 1.0
 */
class DashBoardController extends DashBoardModel
{

    /**
     *
     * Function used to get disclaimer note from database.
     *
     */
    public function getDisclaimerNote()
    {

        return self::actionGetOptionValue(array('key' => 'disclaimer_note'));
    }

    /**
     *
     * Function used to edit and save disclaimer note to the database.
     *
     */
    public function editNote($params = array())
    {
        $params['value'] = self::stripTags($params['value']);
        $resp = array();
        $resp['error'] = true;

        if (self::actionSaveOptions($params)) {
            $resp['error'] = false;
        }

        echo json_encode($resp);
    }

    /**
     * strips only the provided tags with feature to skip/remove it's content too
     * @param string $str - must be HTML content
     * @param array $tags - can accept var also
     * @param bool $strip_content
     *        - default false, it will not remove the content inside the tags
     *        - true, it will remove the contents inside the tags
     *
     * @return var $str
     **/
    public static function stripTags($str, $strip_content = false)
    {
        $content = '';

        $tags = array('script');

        foreach ($tags as $tag) {
            if ($strip_content) {
                $content = '(.+</' . $tag . '[^>]*>|)';
            }
            $str = preg_replace('#</?' . $tag . '[^>]*>' . $content . '#is', '', $str);
        }

        return $str;
    }

}
