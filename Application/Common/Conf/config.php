<?php
return array(
	//'配置项'=>'配置值'
	'SVN_DATA_PATH'	    => '/alidata/svndata/trunk',
	'EXCLUDE_PATH_NAME'	=> ['.','..','.svn','branches','test'],
    'RSYNC_FILE_SET'    => [
        'zhikao100' =>[
            'ip'        => '*.*.*.*',
            'module'    => 'www',
            'exclude'   =>'--exclude=".svn"'
        ]
    ],


    'HTML_TO_CHAR'     =>[
            '|'     => '/',
            '@1@'   => '.html',
            '@2@'   => '.php',
            '@3@'   => '.js',
            '@4@'   => '.jsp',
    ]

);