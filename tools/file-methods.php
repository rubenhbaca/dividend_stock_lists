<?php

/* 
 * php delete function that deals with directories recursively
 * 
 * example
 * delete_files('/path/for/the/directory/');
 */
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
        
        foreach( $files as $file )
        {
            delete_files( $file );      
        }
      
        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}

function get_list_files($target){
    if(!is_dir($target)) return array();

    return glob( $target . '*', GLOB_MARK );
}

function get_json_file($target){
    if(!is_file($target) || !file_exists($target)){
        return false;
    }

    return json_decode(file_get_contents($target), true);
}

function set_json_file($target, $json){
    if(file_exists($target) && !is_file($target)){
        return false;
    }

    file_put_contents($target, json_encode($json, JSON_PRETTY_PRINT));
}