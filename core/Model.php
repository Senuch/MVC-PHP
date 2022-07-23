<?php

require_once(__DIR__.'/database/Connection.php');

class Model
{
    /**
     * @var mysqli|void
     */
    private $connection;

    public function __construct()
    {
        $db = new Connection();
        $connection = $db->getConnection();
    }

    function create($tableName,$insertWhat){

        $sql='INSERT INTO '.$tableName.'(';
        foreach ($insertWhat as $key => $value)
            $sql .= $key.',';

        $sql=rtrim($sql,',');
        $sql.=')';
        $sql.=' VALUES(';

        foreach ($insertWhat as $key => $value)
            $sql .= '\''.$value.'\',';

        $sql=rtrim($sql,',');
        $sql.=')';

        $sql=$this->appendSemicolon($sql);

        $result = $this->connection->query($sql);
        if($result)
            return $result;
        else
            return 'Error at CJ_MODEL/create';
    }

    function read($tableName,$args,$whereArgs){

        $sql='SELECT ';

        foreach ($args as $key => $value)
            $sql.=$value.',';
        $sql=rtrim($sql,',');
        $sql.=' FROM '.$tableName;

        if($whereArgs)
            $sql= $this->where($sql,$whereArgs);

        $sql=$this->appendSemicolon($sql);
        $finale=array();

        $result = $this->connection->query($sql);
        if($result){
            while($row=mysqli_fetch_assoc($result))
                array_push($finale,$row);
            return $finale;
        }
        else
            return 'Error at CJ_MODEL/read';

    }

    function update($tableName,$whatToSet,$whereArgs){
        $sql='UPDATE '.$tableName .' SET ';
        foreach ($whatToSet as $key => $value)
            $sql .= $key .'=\'' . $value . '\',';
        $sql=rtrim($sql,',');

        if($whereArgs)
            $sql= $this->where($sql,$whereArgs);
        $sql=$this->appendSemicolon($sql);
        echo $sql.'<br>';
        $result = $this->connection->query($sql);

        if($result)
            return $result;
        else
            return 'Error at CJ_MODEL/update';

    }

    function delete($tableName,$whereArgs){
        $sql='DELETE FROM '.$tableName;

        if($whereArgs)
            $sql=$this->where($sql,$whereArgs);
        $sql=$this->appendSemicolon($sql);
        echo $sql.'<br>';
        $result = $this->connection->query($sql);

        if($result)
            return $result;
        else
            return 'Error at CJ_MODEL/delete';


    }

    function where($sql,$whereArgs){
        $sql.=' WHERE ';

        foreach ($whereArgs as $key => $value)
            $sql.=$key.' = \''.$value.'\' AND ';
        $sql=rtrim($sql,'AND ');

        return $sql;
    }

    function appendSemicolon($sql){
        if(substr($sql,-1)!=';')
            return $sql.' ;';
    }
}