<?php
return array(
	//'配置项'=>'配置值'
	'SVN_DATA_PATH'	    => '/alidata/svndata',
	'EXCLUDE_PATH_NAME'	=> ['.','..','.svn','branches','test'],
    'RSYNC_FILE_SET'    => [
        'zhikao100' =>[
            'web'   => [
                'ip'        => '139.196.14.115',
                'module'    => 'web',
                'exclude'   =>'--exclude=.svn'
            ],
            'admin'   => [
                'ip'        => '139.196.14.115',
                'module'    => 'admin',
                'exclude'   =>'--exclude=.svn'
            ]

        ]
    ]

);