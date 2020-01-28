<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: agua
//CLASSE DA ENTIDADE aguacondominiomat
class cl_aguacondominiomat { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $x40_matric = 0; 
   var $x40_codcondominio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x40_matric = int4 = Matrícula 
                 x40_codcondominio = int4 = Código do Condomínio 
                 ";
   //funcao construtor da classe 
   function cl_aguacondominiomat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacondominiomat"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->x40_matric = ($this->x40_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_matric"]:$this->x40_matric);
       $this->x40_codcondominio = ($this->x40_codcondominio == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_codcondominio"]:$this->x40_codcondominio);
     }else{
       $this->x40_matric = ($this->x40_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_matric"]:$this->x40_matric);
       $this->x40_codcondominio = ($this->x40_codcondominio == ""?@$GLOBALS["HTTP_POST_VARS"]["x40_codcondominio"]:$this->x40_codcondominio);
     }
   }
   // funcao para inclusao
   function incluir ($x40_codcondominio,$x40_matric){ 
      $this->atualizacampos();
       $this->x40_codcondominio = $x40_codcondominio; 
       $this->x40_matric = $x40_matric; 
     if(($this->x40_codcondominio == null) || ($this->x40_codcondominio == "") ){ 
       $this->erro_sql = " Campo x40_codcondominio nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->x40_matric == null) || ($this->x40_matric == "") ){ 
       $this->erro_sql = " Campo x40_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacondominiomat(
                                       x40_matric 
                                      ,x40_codcondominio 
                       )
                values (
                                $this->x40_matric 
                               ,$this->x40_codcondominio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacondominiomat ($this->x40_codcondominio."-".$this->x40_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacondominiomat já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacondominiomat ($this->x40_codcondominio."-".$this->x40_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcondominio."-".$this->x40_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x40_codcondominio,$this->x40_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8442,'$this->x40_codcondominio','I')");
       $resac = db_query("insert into db_acountkey values($acount,8439,'$this->x40_matric','I')");
       $resac = db_query("insert into db_acount values($acount,1434,8439,'','".AddSlashes(pg_result($resaco,0,'x40_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1434,8442,'','".AddSlashes(pg_result($resaco,0,'x40_codcondominio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x40_codcondominio=null,$x40_matric=null) { 
      $this->atualizacampos();
     $sql = " update aguacondominiomat set ";
     $virgula = "";
     if(trim($this->x40_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_matric"])){ 
       $sql  .= $virgula." x40_matric = $this->x40_matric ";
       $virgula = ",";
       if(trim($this->x40_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x40_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x40_codcondominio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x40_codcondominio"])){ 
       $sql  .= $virgula." x40_codcondominio = $this->x40_codcondominio ";
       $virgula = ",";
       if(trim($this->x40_codcondominio) == null ){ 
         $this->erro_sql = " Campo Código do Condomínio nao Informado.";
         $this->erro_campo = "x40_codcondominio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x40_codcondominio!=null){
       $sql .= " x40_codcondominio = $this->x40_codcondominio";
     }
     if($x40_matric!=null){
       $sql .= " and  x40_matric = $this->x40_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x40_codcondominio,$this->x40_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8442,'$this->x40_codcondominio','A')");
         $resac = db_query("insert into db_acountkey values($acount,8439,'$this->x40_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_matric"]))
           $resac = db_query("insert into db_acount values($acount,1434,8439,'".AddSlashes(pg_result($resaco,$conresaco,'x40_matric'))."','$this->x40_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x40_codcondominio"]))
           $resac = db_query("insert into db_acount values($acount,1434,8442,'".AddSlashes(pg_result($resaco,$conresaco,'x40_codcondominio'))."','$this->x40_codcondominio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacondominiomat nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcondominio."-".$this->x40_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacondominiomat nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcondominio."-".$this->x40_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x40_codcondominio."-".$this->x40_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x40_codcondominio=null,$x40_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x40_codcondominio,$x40_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8442,'$x40_codcondominio','E')");
         $resac = db_query("insert into db_acountkey values($acount,8439,'$x40_matric','E')");
         $resac = db_query("insert into db_acount values($acount,1434,8439,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1434,8442,'','".AddSlashes(pg_result($resaco,$iresaco,'x40_codcondominio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacondominiomat
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x40_codcondominio != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x40_codcondominio = $x40_codcondominio ";
        }
        if($x40_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x40_matric = $x40_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacondominiomat nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x40_codcondominio."-".$x40_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacondominiomat nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x40_codcondominio."-".$x40_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x40_codcondominio."-".$x40_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:aguacondominiomat";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x40_codcondominio=null,$x40_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from aguacondominiomat ";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacondominiomat.x40_matric";
     $sql .= "      inner join aguacondominio  on  aguacondominio.x31_codcondominio = aguacondominiomat.x40_codcondominio";
     $sql .= "      left  join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      left  join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      inner join aguabase a  on  a.x01_matric = aguacondominio.x31_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($x40_codcondominio!=null ){
         $sql2 .= " where aguacondominiomat.x40_codcondominio = $x40_codcondominio "; 
       } 
       if($x40_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguacondominiomat.x40_matric = $x40_matric "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $x40_codcondominio=null,$x40_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from aguacondominiomat ";
     $sql2 = "";
     if($dbwhere==""){
       if($x40_codcondominio!=null ){
         $sql2 .= " where aguacondominiomat.x40_codcondominio = $x40_codcondominio "; 
       } 
       if($x40_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguacondominiomat.x40_matric = $x40_matric "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>