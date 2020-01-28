<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Inflatores
//CLASSE DA ENTIDADE inflan
class cl_inflan { 
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
   var $i01_codigo = null; 
   var $i01_descr = null; 
   var $i01_pict = null; 
   var $i01_dm = null; 
   var $i01_tipo = null; 
   var $i01_percen = null; 
   var $i01_calc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 i01_codigo = varchar(5) = codigo do inflator 
                 i01_descr = varchar(40) = descricao do inflator 
                 i01_pict = varchar(12) = picture 
                 i01_dm = char(1) = Tipo de Lançamento 
                 i01_tipo = varchar(1) = tipo do inflator 
                 i01_percen = varchar(1) = Percen 
                 i01_calc = int4 = Tipo de calculo 
                 ";
   //funcao construtor da classe 
   function cl_inflan() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inflan"); 
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
       $this->i01_codigo = ($this->i01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_codigo"]:$this->i01_codigo);
       $this->i01_descr = ($this->i01_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_descr"]:$this->i01_descr);
       $this->i01_pict = ($this->i01_pict == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_pict"]:$this->i01_pict);
       $this->i01_dm = ($this->i01_dm == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_dm"]:$this->i01_dm);
       $this->i01_tipo = ($this->i01_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_tipo"]:$this->i01_tipo);
       $this->i01_percen = ($this->i01_percen == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_percen"]:$this->i01_percen);
       $this->i01_calc = ($this->i01_calc == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_calc"]:$this->i01_calc);
     }else{
       $this->i01_codigo = ($this->i01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i01_codigo"]:$this->i01_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($i01_codigo){ 
      $this->atualizacampos();
     if($this->i01_descr == null ){ 
       $this->erro_sql = " Campo descricao do inflator nao Informado.";
       $this->erro_campo = "i01_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i01_dm == null ){ 
       $this->erro_sql = " Campo Tipo de Lançamento nao Informado.";
       $this->erro_campo = "i01_dm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i01_tipo == null ){ 
       $this->erro_sql = " Campo tipo do inflator nao Informado.";
       $this->erro_campo = "i01_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i01_percen == null ){ 
       $this->erro_sql = " Campo Percen nao Informado.";
       $this->erro_campo = "i01_percen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i01_calc == null ){ 
       $this->erro_sql = " Campo Tipo de calculo nao Informado.";
       $this->erro_campo = "i01_calc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->i01_codigo = $i01_codigo; 
     if(($this->i01_codigo == null) || ($this->i01_codigo == "") ){ 
       $this->erro_sql = " Campo i01_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inflan(
                                       i01_codigo 
                                      ,i01_descr 
                                      ,i01_pict 
                                      ,i01_dm 
                                      ,i01_tipo 
                                      ,i01_percen 
                                      ,i01_calc 
                       )
                values (
                                '$this->i01_codigo' 
                               ,'$this->i01_descr' 
                               ,'$this->i01_pict' 
                               ,'$this->i01_dm' 
                               ,'$this->i01_tipo' 
                               ,'$this->i01_percen' 
                               ,$this->i01_calc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->i01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->i01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i01_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->i01_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,440,'$this->i01_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,80,440,'','".AddSlashes(pg_result($resaco,0,'i01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,80,441,'','".AddSlashes(pg_result($resaco,0,'i01_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,80,442,'','".AddSlashes(pg_result($resaco,0,'i01_pict'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,80,443,'','".AddSlashes(pg_result($resaco,0,'i01_dm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,80,444,'','".AddSlashes(pg_result($resaco,0,'i01_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,80,7384,'','".AddSlashes(pg_result($resaco,0,'i01_percen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,80,7146,'','".AddSlashes(pg_result($resaco,0,'i01_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($i01_codigo=null) { 
      $this->atualizacampos();
     $sql = " update inflan set ";
     $virgula = "";
     if(trim($this->i01_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_codigo"])){ 
       $sql  .= $virgula." i01_codigo = '$this->i01_codigo' ";
       $virgula = ",";
       if(trim($this->i01_codigo) == null ){ 
         $this->erro_sql = " Campo codigo do inflator nao Informado.";
         $this->erro_campo = "i01_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i01_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_descr"])){ 
       $sql  .= $virgula." i01_descr = '$this->i01_descr' ";
       $virgula = ",";
       if(trim($this->i01_descr) == null ){ 
         $this->erro_sql = " Campo descricao do inflator nao Informado.";
         $this->erro_campo = "i01_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i01_pict)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_pict"])){ 
       $sql  .= $virgula." i01_pict = '$this->i01_pict' ";
       $virgula = ",";
     }
     if(trim($this->i01_dm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_dm"])){ 
       $sql  .= $virgula." i01_dm = '$this->i01_dm' ";
       $virgula = ",";
       if(trim($this->i01_dm) == null ){ 
         $this->erro_sql = " Campo Tipo de Lançamento nao Informado.";
         $this->erro_campo = "i01_dm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i01_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_tipo"])){ 
       $sql  .= $virgula." i01_tipo = '$this->i01_tipo' ";
       $virgula = ",";
       if(trim($this->i01_tipo) == null ){ 
         $this->erro_sql = " Campo tipo do inflator nao Informado.";
         $this->erro_campo = "i01_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i01_percen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_percen"])){ 
       $sql  .= $virgula." i01_percen = '$this->i01_percen' ";
       $virgula = ",";
       if(trim($this->i01_percen) == null ){ 
         $this->erro_sql = " Campo Percen nao Informado.";
         $this->erro_campo = "i01_percen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i01_calc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i01_calc"])){ 
       $sql  .= $virgula." i01_calc = $this->i01_calc ";
       $virgula = ",";
       if(trim($this->i01_calc) == null ){ 
         $this->erro_sql = " Campo Tipo de calculo nao Informado.";
         $this->erro_campo = "i01_calc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($i01_codigo!=null){
       $sql .= " i01_codigo = '$this->i01_codigo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->i01_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,440,'$this->i01_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_codigo"]) || $this->i01_codigo != "")
           $resac = db_query("insert into db_acount values($acount,80,440,'".AddSlashes(pg_result($resaco,$conresaco,'i01_codigo'))."','$this->i01_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_descr"]) || $this->i01_descr != "")
           $resac = db_query("insert into db_acount values($acount,80,441,'".AddSlashes(pg_result($resaco,$conresaco,'i01_descr'))."','$this->i01_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_pict"]) || $this->i01_pict != "")
           $resac = db_query("insert into db_acount values($acount,80,442,'".AddSlashes(pg_result($resaco,$conresaco,'i01_pict'))."','$this->i01_pict',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_dm"]) || $this->i01_dm != "")
           $resac = db_query("insert into db_acount values($acount,80,443,'".AddSlashes(pg_result($resaco,$conresaco,'i01_dm'))."','$this->i01_dm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_tipo"]) || $this->i01_tipo != "")
           $resac = db_query("insert into db_acount values($acount,80,444,'".AddSlashes(pg_result($resaco,$conresaco,'i01_tipo'))."','$this->i01_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_percen"]) || $this->i01_percen != "")
           $resac = db_query("insert into db_acount values($acount,80,7384,'".AddSlashes(pg_result($resaco,$conresaco,'i01_percen'))."','$this->i01_percen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i01_calc"]) || $this->i01_calc != "")
           $resac = db_query("insert into db_acount values($acount,80,7146,'".AddSlashes(pg_result($resaco,$conresaco,'i01_calc'))."','$this->i01_calc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->i01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->i01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($i01_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($i01_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,440,'$i01_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,80,440,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,80,441,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,80,442,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_pict'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,80,443,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_dm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,80,444,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,80,7384,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_percen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,80,7146,'','".AddSlashes(pg_result($resaco,$iresaco,'i01_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inflan
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($i01_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " i01_codigo = '$i01_codigo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$i01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$i01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$i01_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:inflan";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $i01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inflan ";
     $sql2 = "";
     if($dbwhere==""){
       if($i01_codigo!=null ){
         $sql2 .= " where inflan.i01_codigo = '$i01_codigo' "; 
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
   // funcao do sql 
   function sql_query_file ( $i01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inflan ";
     $sql2 = "";
     if($dbwhere==""){
       if($i01_codigo!=null ){
         $sql2 .= " where inflan.i01_codigo = '$i01_codigo' "; 
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