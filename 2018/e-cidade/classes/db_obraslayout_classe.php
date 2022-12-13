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

//MODULO: projetos
//CLASSE DA ENTIDADE obraslayout
class cl_obraslayout { 
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
   var $ob14_seq = 0; 
   var $ob14_codobra = 0; 
   var $ob14_codhab = 0; 
   var $ob14_data_dia = null; 
   var $ob14_data_mes = null; 
   var $ob14_data_ano = null; 
   var $ob14_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob14_seq = int4 = C�digo 
                 ob14_codobra = int4 = C�digo da obra 
                 ob14_codhab = int4 = C�digo do habite-se 
                 ob14_data = date = Data da emiss�o 
                 ";
   //funcao construtor da classe 
   function cl_obraslayout() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obraslayout"); 
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
       $this->ob14_seq = ($this->ob14_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_seq"]:$this->ob14_seq);
       $this->ob14_codobra = ($this->ob14_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_codobra"]:$this->ob14_codobra);
       $this->ob14_codhab = ($this->ob14_codhab == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_codhab"]:$this->ob14_codhab);
       if($this->ob14_data == ""){
         $this->ob14_data_dia = ($this->ob14_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_data_dia"]:$this->ob14_data_dia);
         $this->ob14_data_mes = ($this->ob14_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_data_mes"]:$this->ob14_data_mes);
         $this->ob14_data_ano = ($this->ob14_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_data_ano"]:$this->ob14_data_ano);
         if($this->ob14_data_dia != ""){
            $this->ob14_data = $this->ob14_data_ano."-".$this->ob14_data_mes."-".$this->ob14_data_dia;
         }
       }
     }else{
       $this->ob14_seq = ($this->ob14_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ob14_seq"]:$this->ob14_seq);
     }
   }
   // funcao para inclusao
   function incluir ($ob14_seq){ 
      $this->atualizacampos();
     if($this->ob14_codobra == null ){ 
       $this->erro_sql = " Campo C�digo da obra nao Informado.";
       $this->erro_campo = "ob14_codobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob14_codhab == null ){ 
       $this->erro_sql = " Campo C�digo do habite-se nao Informado.";
       $this->erro_campo = "ob14_codhab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob14_data == null ){ 
       $this->erro_sql = " Campo Data da emiss�o nao Informado.";
       $this->erro_campo = "ob14_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob14_seq == "" || $ob14_seq == null ){
       $result = db_query("select nextval('obraslayout_ob14_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obraslayout_ob14_seq_seq do campo: ob14_seq"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob14_seq = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obraslayout_ob14_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob14_seq)){
         $this->erro_sql = " Campo ob14_seq maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob14_seq = $ob14_seq; 
       }
     }
     if(($this->ob14_seq == null) || ($this->ob14_seq == "") ){ 
       $this->erro_sql = " Campo ob14_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obraslayout(
                                       ob14_seq 
                                      ,ob14_codobra 
                                      ,ob14_codhab 
                                      ,ob14_data 
                       )
                values (
                                $this->ob14_seq 
                               ,$this->ob14_codobra 
                               ,$this->ob14_codhab 
                               ,".($this->ob14_data == "null" || $this->ob14_data == ""?"null":"'".$this->ob14_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "controle de layout do modulo obras ($this->ob14_seq) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "controle de layout do modulo obras j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "controle de layout do modulo obras ($this->ob14_seq) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob14_seq;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ob14_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6034,'$this->ob14_seq','I')");
       $resac = db_query("insert into db_acount values($acount,968,6034,'','".AddSlashes(pg_result($resaco,0,'ob14_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,968,6035,'','".AddSlashes(pg_result($resaco,0,'ob14_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,968,6036,'','".AddSlashes(pg_result($resaco,0,'ob14_codhab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,968,6042,'','".AddSlashes(pg_result($resaco,0,'ob14_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob14_seq=null) { 
      $this->atualizacampos();
     $sql = " update obraslayout set ";
     $virgula = "";
     if(trim($this->ob14_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob14_seq"])){ 
       $sql  .= $virgula." ob14_seq = $this->ob14_seq ";
       $virgula = ",";
       if(trim($this->ob14_seq) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "ob14_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob14_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob14_codobra"])){ 
       $sql  .= $virgula." ob14_codobra = $this->ob14_codobra ";
       $virgula = ",";
       if(trim($this->ob14_codobra) == null ){ 
         $this->erro_sql = " Campo C�digo da obra nao Informado.";
         $this->erro_campo = "ob14_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob14_codhab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob14_codhab"])){ 
       $sql  .= $virgula." ob14_codhab = $this->ob14_codhab ";
       $virgula = ",";
       if(trim($this->ob14_codhab) == null ){ 
         $this->erro_sql = " Campo C�digo do habite-se nao Informado.";
         $this->erro_campo = "ob14_codhab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob14_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob14_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob14_data_dia"] !="") ){ 
       $sql  .= $virgula." ob14_data = '$this->ob14_data' ";
       $virgula = ",";
       if(trim($this->ob14_data) == null ){ 
         $this->erro_sql = " Campo Data da emiss�o nao Informado.";
         $this->erro_campo = "ob14_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob14_data_dia"])){ 
         $sql  .= $virgula." ob14_data = null ";
         $virgula = ",";
         if(trim($this->ob14_data) == null ){ 
           $this->erro_sql = " Campo Data da emiss�o nao Informado.";
           $this->erro_campo = "ob14_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ob14_seq!=null){
       $sql .= " ob14_seq = $this->ob14_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ob14_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6034,'$this->ob14_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob14_seq"]))
           $resac = db_query("insert into db_acount values($acount,968,6034,'".AddSlashes(pg_result($resaco,$conresaco,'ob14_seq'))."','$this->ob14_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob14_codobra"]))
           $resac = db_query("insert into db_acount values($acount,968,6035,'".AddSlashes(pg_result($resaco,$conresaco,'ob14_codobra'))."','$this->ob14_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob14_codhab"]))
           $resac = db_query("insert into db_acount values($acount,968,6036,'".AddSlashes(pg_result($resaco,$conresaco,'ob14_codhab'))."','$this->ob14_codhab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ob14_data"]))
           $resac = db_query("insert into db_acount values($acount,968,6042,'".AddSlashes(pg_result($resaco,$conresaco,'ob14_data'))."','$this->ob14_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "controle de layout do modulo obras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob14_seq;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "controle de layout do modulo obras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob14_seq;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob14_seq;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob14_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ob14_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6034,'$ob14_seq','E')");
         $resac = db_query("insert into db_acount values($acount,968,6034,'','".AddSlashes(pg_result($resaco,$iresaco,'ob14_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,968,6035,'','".AddSlashes(pg_result($resaco,$iresaco,'ob14_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,968,6036,'','".AddSlashes(pg_result($resaco,$iresaco,'ob14_codhab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,968,6042,'','".AddSlashes(pg_result($resaco,$iresaco,'ob14_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from obraslayout
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob14_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob14_seq = $ob14_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "controle de layout do modulo obras nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob14_seq;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "controle de layout do modulo obras nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob14_seq;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob14_seq;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:obraslayout";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ob14_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obraslayout ";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obraslayout.ob14_codobra";
     $sql .= "      inner join obrashabite  on  obrashabite.ob09_codhab = obraslayout.ob14_codhab";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql .= "      inner join obrasconstr  on  obrasconstr.ob08_codconstr = obrashabite.ob09_codconstr";
     $sql2 = "";
     if($dbwhere==""){
       if($ob14_seq!=null ){
         $sql2 .= " where obraslayout.ob14_seq = $ob14_seq "; 
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
   function sql_query_file ( $ob14_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obraslayout ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob14_seq!=null ){
         $sql2 .= " where obraslayout.ob14_seq = $ob14_seq "; 
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