#!/bin/bash

if [ "$1" ] ; then
    if [ $1 = '-c' ] && [ $2"x" != 'x'  ] ; then
        shift
        cat $1 | while read hash_line
        do
            hash_value=`echo $hash_line | awk '{print $1}'`
            filename=`echo $hash_line | awk '{print $2}'`
            echo -n $filename": "
            if [ -f "$filename" ] ; then
                hash_var=`openssl dgst -md5 $filename | awk '{print $2}'` ;
                if [ $hash_var == $hash_value ] ; then
                    echo "OK"
                else
                    echo "FAILED"
                fi
            else
                echo "FAILED: No such file or directory"
            fi
        done
    else
        openssl dgst -md5 $*  |  sed 's/[\(\)=]//g;s/MD5//g' | awk '{print $2"  "$1}'
    fi
else 
    echo "Usage:
    $0 -c hashs.md5      to verify checksums
    $0  <file>           to create checksums
    "
fi