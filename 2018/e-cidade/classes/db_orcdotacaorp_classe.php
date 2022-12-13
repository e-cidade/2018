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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcdotacaorp
class cl_orcdotacaorp { 
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
   var $o73_anousu = 0; 
   var $o73_coddot = 0; 
   var $o73_funcao = 0; 
   var $o73_subfuncao = 0; 
   var $o73_subprograma = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o73_anousu = int4 = Ano 
                 o73_coddot = int4 = Reduzido 
                 o73_funcao = int4 = Fun��o 
                 o73_subfuncao = int4 = Sub Fun��o 
                 o73_subprograma = int8 = Subprograma 
                 ";
   //funcao construtor da classe 
   function cl_orcdotacaorp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcdotacaorp"); 
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
       $this->o73_anousu = ($this->o73_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_anousu"]:$this->o73_anousu);
       $this->o73_coddot = ($this->o73_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_coddot"]:$this->o73_coddot);
       $this->o73_funcao = ($this->o73_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_funcao"]:$this->o73_funcao);
       $this->o73_subfuncao = ($this->o73_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_subfuncao"]:$this->o73_subfuncao);
       $this->o73_subprograma = ($this->o73_subprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_subprograma"]:$this->o73_subprograma);
     }else{
       $this->o73_anousu = ($this->o73_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_anousu"]:$this->o73_anousu);
       $this->o73_coddot = ($this->o73_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o73_coddot"]:$this->o73_coddot);
     }
   }
   // funcao para inclusao
   function incluir ($o73_anousu,$o73_coddot){ 
      $this->atualizacampos();
     if($this->o73_funcao == null ){ 
       $this->erro_sql = " Campo Fun��o nao Informado.";
       $this->erro_campo = "o73_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o73_subfuncao == null ){ 
       $this->erro_sql = " Campo Sub Fun��o nao Informado.";
       $this->erro_campo = "o73_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o73_subprograma == null ){ 
       $this->erro_sql = " Campo Subprograma nao Informado.";
       $this->erro_campo = "o73_subprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o73_anousu = $o73_anousu; 
       $this->o73_coddot = $o73_coddot; 
     if(($this->o73_anousu == null) || ($this->o73_anousu == "") ){ 
       $this->erro_sql = " Campo o73_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o73_coddot == null) || ($this->o73_coddot == "") ){ 
       $this->erro_sql = " Campo o73_coddot nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcdotacaorp(
                                       o73_anousu 
                                      ,o73_coddot 
                                      ,o73_funcao 
                                      ,o73_subfuncao 
                                      ,o73_subprograma 
                       )
                values (
                                $this->o73_anousu 
                               ,$this->o73_coddot 
                               ,$this->o73_funcao 
                               ,$this->o73_subfuncao 
                               ,$this->o73_subprograma 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dota��es dos RP's ($this->o73_anousu."-".$this->o73_coddot) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dota��es dos RP's j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dota��es dos RP's ($this->o73_anousu."-".$this->o73_coddot) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o73_anousu."-".$this->o73_coddot;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o73_anousu,$this->o73_coddot));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6512,'$this->o73_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,6513,'$this->o73_coddot','I')");
       $resac = db_query("insert into db_acount values($acount,1073,6512,'','".AddSlashes(pg_result($resaco,0,'o73_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1073,6513,'','".AddSlashes(pg_result($resaco,0,'o73_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1073,6514,'','".AddSlashes(pg_result($resaco,0,'o73_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1073,6515,'','".AddSlashes(pg_result($resaco,0,'o73_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1073,6516,'','".AddSlashes(pg_result($resaco,0,'o73_subprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o73_anousu=null,$o73_coddot=null) { 
      $this->atualizacampos();
     $sql = " update orcdotacaorp set ";
     $virgula = "";
     if(trim($this->o73_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o73_anousu"])){ 
       $sql  .= $virgula." o73_anousu = $this->o73_anousu ";
       $virgula = ",";
       if(trim($this->o73_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o73_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o73_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o73_coddot"])){ 
       $sql  .= $virgula." o73_coddot = $this->o73_coddot ";
       $virgula = ",";
       if(trim($this->o73_coddot) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "o73_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o73_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o73_funcao"])){ 
       $sql  .= $virgula." o73_funcao = $this->o73_funcao ";
       $virgula = ",";
       if(trim($this->o73_funcao) == null ){ 
         $this->erro_sql = " Campo Fun��o nao Informado.";
         $this->erro_campo = "o73_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o73_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o73_subfuncao"])){ 
       $sql  .= $virgula." o73_subfuncao = $this->o73_subfuncao ";
       $virgula = ",";
       if(trim($this->o73_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Fun��o nao Informado.";
         $this->erro_campo = "o73_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o73_subprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o73_subprograma"])){ 
       $sql  .= $virgula." o73_subprograma = $this->o73_subprograma ";
       $virgula = ",";
       if(trim($this->o73_subprograma) == null ){ 
         $this->erro_sql = " Campo Subprograma nao Informado.";
         $this->erro_campo = "o73_subprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o73_anousu!=null){
       $sql .= " o73_anousu = $this->o73_anousu";
     }
     if($o73_coddot!=null){
       $sql .= " and  o73_coddot = $this->o73_coddot";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o73_anousu,$this->o73_coddot));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6512,'$this->o73_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,6513,'$this->o73_coddot','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o73_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1073,6512,'".AddSlashes(pg_result($resaco,$conresaco,'o73_anousu'))."','$this->o73_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o73_coddot"]))
           $resac = db_query("insert into db_acount values($acount,1073,6513,'".AddSlashes(pg_result($resaco,$conresaco,'o73_coddot'))."','$this->o73_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o73_funcao"]))
           $resac = db_query("insert into db_acount values($acount,1073,6514,'".AddSlashes(pg_result($resaco,$conresaco,'o73_funcao'))."','$this->o73_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o73_subfuncao"]))
           $resac = db_query("insert into db_acount values($acount,1073,6515,'".AddSlashes(pg_result($resaco,$conresaco,'o73_subfuncao'))."','$this->o73_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o73_subprograma"]))
           $resac = db_query("insert into db_acount values($acount,1073,6516,'".AddSlashes(pg_result($resaco,$conresaco,'o73_subprograma'))."','$this->o73_subprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dota��es dos RP's nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o73_anousu."-".$this->o73_coddot;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dota��es dos RP's nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o73_anousu."-".$this->o73_coddot;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o73_anousu."-".$this->o73_coddot;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o73_anousu=null,$o73_coddot=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o73_anousu,$o73_coddot));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6512,'$o73_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,6513,'$o73_coddot','E')");
         $resac = db_query("insert into db_acount values($acount,1073,6512,'','".AddSlashes(pg_result($resaco,$iresaco,'o73_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1073,6513,'','".AddSlashes(pg_result($resaco,$iresaco,'o73_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1073,6514,'','".AddSlashes(pg_result($resaco,$iresaco,'o73_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1073,6515,'','".AddSlashes(pg_result($resaco,$iresaco,'o73_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1073,6516,'','".AddSlashes(pg_result($resaco,$iresaco,'o73_subprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcdotacaorp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o73_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o73_anousu = $o73_anousu ";
        }
        if($o73_coddot != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o73_coddot = $o73_coddot ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dota��es dos RP's nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o73_anousu."-".$o73_coddot;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dota��es dos RP's nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o73_anousu."-".$o73_coddot;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o73_anousu."-".$o73_coddot;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcdotacaorp";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>