<?php
/**
 * Task
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Common
 * @subpackage  Task
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-09-19 09:34:20
 * @todo        分配报表,检查人员,前置任务,后置任务,一个任务分配给多个人
 */

class Common_Task_Metadata_Task extends Common_Metadata
{
    public function  __construct() 
    {
        $this->setCommonMetadata();
        $this->parseMetadata(array(
            'field' => array(
                'name' => array(
                    'validator' => array(
                        'rule' => array(
                            'required' => true,
                        ),
                    ),
                ),
                'description' => array(
                    'form' => array(
                        '_type' => 'textarea',
                        '_widget' => 'CKEditor',
                    ),
                    'attr' => array(
                        'isList' => 0,
                    ),
                ),
                'status' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'converter' => array(
                        'list' => array(
                            array('Project_Helper_CommonClass', 'convert'),
                            'task-status',
                        ),
                        'view' => 'list'
                    ),
                    'attr' => array(
                        //'isLink' => 1,
                    ),
                ),
                'assign_to' => array(
                     'form' => array(
                        '_type' => 'select',
                        '_resourceGetter' => array(
                            array('Project_Helper_Category', 'getTreeResource'),
                            array(
                                'namespace' => 'Common',
                                'module' => 'Member',
                                'controller' => 'Member',
                            ),
                            null,
                            array('id', null, 'username'),
                        ),
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                    'validator' => array(
                        'rule' => array(
                            'notNull' => true,
                        ),
                    ),
                ),
                'is_post_email' => array(
                    'form' => array(
                        '_type' => 'checkbox',
                        '_resourceGetter' => array(
                            array('Project_Helper_CommonClass', 'get'),
                            'yes',
                        ),
                        '_value' => 1,
                    ),
                    'attr' => array(
                        'isDbField' => 0,
                        'isList' => 0,
                    ),
                ),
                'assign_by' => array(
                    'form' => array(
                        '_type' => 'hidden',
                    ),
                    'attr' => array(
                        'isLink' => 1,
                    ),
                ),
            ),
            'model' => array(
                'assign_by' => array(
                    'name' => 'Common_Member_Model_Member',
                    'alias' => 'assign-by',
                    'metadata' => 'Common_Member_Metadata_Member',
                    'local' => 'assign_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'assign_by' => 'username',
                    ),
                ),
                'assign_to' => array(
                    'name' => 'Common_Member_Model_Member',
                    'alias' => 'assign-to',
                    'metadata' => 'Common_Member_Metadata_Member',
                    'local' => 'assign_to',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'assign_to' => 'username',
                    ),
                ),
                'created_by' => array(
                    'name' => 'Common_Member_Model_Member',
                    'alias' => 'created-by',
                    'metadata' => 'Common_Member_Metadata_Member',
                    'local' => 'created_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'created_by' => 'username',
                    ),
                ),
                'modified_by' => array(
                    'name' => 'Common_Member_Model_Member',
                    'alias' => 'modified-by',
                    'metadata' => 'Common_Member_Metadata_Member',
                    'local' => 'modified_by',
                    'foreign' => 'id',
                    'type' => 'view',
                    'fieldMap' => array(
                        'modified_by' => 'username',
                    ),
                ),
                'status' => array(
                    'name' => 'Common_Task_Model_Status',
                    'alias' => 'status',
                    'metadata' => 'Common_Task_Metadata_Status',
                    'local' => 'id',
                    'foreign' => 'task_id',
                    'type' => 'relatedDb',
                    'fieldMap' => array(
                        'id' => 'task_id',
                        'status' => 'status',
                        'date_modified' => 'date_created',
                        'status_description' => 'description',
                        'modified_by' => 'created_by',
                    ),
                    'set' => array(
                        'namespace' => 'Common',
                        'module' => 'Task',
                        'controller' => 'Status',
                    ),
                ),
                'email' => array(
                    'name' => 'Common_Email_Model_Email',
                    'alias' => 'email',
                    'metadata' => 'Common_Email_Metadata_Email',
                    'local' => 'id',
                    'foreign' => 'foreign_id',
                    'type' => 'relatedDb',
                    'fieldMap' => array(
                        'id' => 'foreign_id',
                        'date_modified' => 'date_created',
                        'modified_by' => 'created_by',
                    ),
                    'set' => array(
                        'namespace' => 'Common',
                        'module' => 'Email',
                        'controller' => 'Email',
                    ),
                ),
            ),
            'db' => array(
                'table' => 'task',
                'order' => array(
                    array('date_created', 'DESC'),
                )
                
            ),
            'page' => array(
                'title' => 'LBL_MODULE_TASK',
            ),
            'shortcut' => array(
                array(
                    
                ),
            ),
        ));
        $this->field->set('operation.list.width', 160);
    }
}
