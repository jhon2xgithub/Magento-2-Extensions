<?php

namespace Shop\Lookbook\File;

use Magento\Framework\Filesystem\DriverInterface;


class Uploader extends \Magento\Framework\File\Uploader
{
    /**
     * Uploaded file handle (copy of $_FILES[] element)
     *
     * @var array
     * @access protected
     */
    protected $_file;

    /**
     * Uploaded file mime type
     *
     * @var string
     * @access protected
     */
    protected $_fileMimeType;

    /**
     * Upload type. Used to right handle $_FILES array.
     *
     * @var \Magento\Framework\File\Uploader::SINGLE_STYLE|\Magento\Framework\File\Uploader::MULTIPLE_STYLE
     * @access protected
     */
    protected $_uploadType;

    /**
     * The name of uploaded file. By default it is original file name, but when
     * we will change file name, this variable will be changed too.
     *
     * @var string
     * @access protected
     */
    protected $_uploadedFileName;

    /**
     * The name of destination directory
     *
     * @var string
     * @access protected
     */
    protected $_uploadedFileDir;

    /**
     * If this variable is set to TRUE, our library will be able to automatically create
     * non-existent directories.
     *
     * @var bool
     * @access protected
     */
    protected $_allowCreateFolders = true;

    /**
     * If this variable is set to TRUE, uploaded file name will be changed if some file with the same
     * name already exists in the destination directory (if enabled).
     *
     * @var bool
     * @access protected
     */
    protected $_allowRenameFiles = false;

    /**
     * If this variable is set to TRUE, files dispertion will be supported.
     *
     * @var bool
     * @access protected
     */
    protected $_enableFilesDispersion = false;

    /**
     * This variable is used both with $_enableFilesDispersion == true
     * It helps to avoid problems after migrating from case-insensitive file system to case-insensitive
     * (e.g. NTFS->ext or ext->NTFS)
     *
     * @var bool
     * @access protected
     */
    protected $_caseInsensitiveFilenames = true;

    /**
     * @var string
     * @access protected
     */
    protected $_dispretionPath = null;

    /**
     * @var bool
     */
    protected $_fileExists = false;

    /**
     * @var null|string[]
     */
    protected $_allowedExtensions = null;

    /**
     * Validate callbacks storage
     *
     * @var array
     * @access protected
     */
    protected $_validateCallbacks = [];

    /**#@+
     * File upload type (multiple or single)
     */
    const SINGLE_STYLE = 0;

    const MULTIPLE_STYLE = 1;

    /**#@-*/

    /**
     * Temp file name empty code
     */
    const TMP_NAME_EMPTY = 666;

    /**
     * Max Image Width resolution in pixels. For image resizing on client side
     */
    const MAX_IMAGE_WIDTH = 1920;

    /**
     * Max Image Height resolution in pixels. For image resizing on client side
     */
    const MAX_IMAGE_HEIGHT = 1200;


}