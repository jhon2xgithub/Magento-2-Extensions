<?php
 
namespace Shop\CmsPdf\Helper\Wysiwyg;
 
class Images extends \Magento\Cms\Helper\Wysiwyg\Images
{    
    /**
     * Prepare Image insertion declaration for Wysiwyg or textarea(as_is mode)
     *
     * @param string $filename Filename transferred via Ajax
     * @param bool $renderAsTag Leave image HTML as is or transform it to controller directive
     * @return string
     */
    public function getImageHtmlDeclaration($filename, $renderAsTag = false)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);  
       
        $fileurl = $this->getCurrentUrl() . $filename;
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $mediaPath = str_replace($mediaUrl, '', $fileurl);
        $directive = sprintf('{{media url="%s"}}', $mediaPath);
        if ($renderAsTag) {

            if($ext == 'pdf'){     
                $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);           
                $html = sprintf('<a href="%s" alt="" target="_blank">'.$file_name.'</a>', $this->isUsingStaticUrlsAllowed() ? $fileurl : $directive);
            }else{
                $html = sprintf('<img src="%s" alt="" />', $this->isUsingStaticUrlsAllowed() ? $fileurl : $directive);
            }

        } else {
            if ($this->isUsingStaticUrlsAllowed()) {
                $html = $fileurl; // $mediaPath;
            } else {
                $directive = $this->urlEncoder->encode($directive);
                $html = $this->_backendData->getUrl('cms/wysiwyg/directive', ['___directive' => $directive]);
            }
        }
        return $html;
    }
}