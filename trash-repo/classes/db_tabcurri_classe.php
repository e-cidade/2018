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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE tabcurri
class cl_tabcurri { 
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
   var $h01_codigo = 0; 
   var $h01_cgmentid = 0; 
   var $h01_descr = null; 
   var $h01_detalh = null; 
   var $h01_codtipo = 0; 
   var $h01_cargahor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h01_codigo = int4 = Codigo do Historico 
                 h01_cgmentid = int4 = Entidade 
                 h01_descr = varchar(30) = Descrição do Histórico 
                 h01_detalh = text = Detalhamento 
                 h01_codtipo = int4 = Tipo do Curso 
                 h01_cargahor = float8 = Carga Horária 
                 ";
   //funcao construtor da classe 
   function cl_tabcurri() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabcurri"); 
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
       $this->h01_codigo = ($this->h01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_codigo"]:$this->h01_codigo);
       $this->h01_cgmentid = ($this->h01_cgmentid == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_cgmentid"]:$this->h01_cgmentid);
       $this->h01_descr = ($this->h01_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_descr"]:$this->h01_descr);
       $this->h01_detalh = ($this->h01_detalh == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_detalh"]:$this->h01_detalh);
       $this->h01_codtipo = ($this->h01_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_codtipo"]:$this->h01_codtipo);
       $this->h01_cargahor = ($this->h01_cargahor == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_cargahor"]:$this->h01_cargahor);
     }else{
       $this->h01_codigo = ($this->h01_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h01_codigo"]:$this->h01_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($h01_codigo){ 
      $this->atualizacampos();
     if($this->h01_cgmentid == null ){ 
       $this->erro_sql = " Campo Entidade nao Informado.";
       $this->erro_campo = "h01_cgmentid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h01_descr == null ){ 
       $this->erro_sql = " Campo Descrição do Histórico nao Informado.";
       $this->erro_campo = "h01_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h01_codtipo == null ){ 
       $this->erro_sql = " Campo Tipo do Curso nao Informado.";
       $this->erro_campo = "h01_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h01_cargahor == null ){ 
       $this->erro_sql = " Campo Carga Horária nao Informado.";
       $this->erro_campo = "h01_cargahor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h01_codigo == "" || $h01_codigo == null ){
       $result = db_query("select nextval('tabcurri_h01_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabcurri_h01_codigo_seq do campo: h01_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h01_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tabcurri_h01_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $h01_codigo)){
         $this->erro_sql = " Campo h01_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h01_codigo = $h01_codigo; 
       }
     }
     if(($this->h01_codigo == null) || ($this->h01_codigo == "") ){ 
       $this->erro_sql = " Campo h01_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabcurri(
                                       h01_codigo 
                                      ,h01_cgmentid 
                                      ,h01_descr 
                                      ,h01_detalh 
                                      ,h01_codtipo 
                                      ,h01_cargahor 
                       )
                values (
                                $this->h01_codigo 
                               ,$this->h01_cgmentid 
                               ,'$this->h01_descr' 
                               ,'$this->h01_detalh' 
                               ,$this->h01_codtipo 
                               ,$this->h01_cargahor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contem a tabela de historicos de ocorrencias no cu ($this->h01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contem a tabela de historicos de ocorrencias no cu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contem a tabela de historicos de ocorrencias no cu ($this->h01_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h01_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h01_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4481,'$this->h01_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,593,4481,'','".AddSlashes(pg_result($resaco,0,'h01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,593,3868,'','".AddSlashes(pg_result($resaco,0,'h01_cgmentid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,593,4482,'','".AddSlashes(pg_result($resaco,0,'h01_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,593,4483,'','".AddSlashes(pg_result($resaco,0,'h01_detalh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,593,9674,'','".AddSlashes(pg_result($resaco,0,'h01_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,593,9672,'','".AddSlashes(pg_result($resaco,0,'h01_cargahor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h01_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tabcurri set ";
     $virgula = "";
     if(trim($this->h01_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h01_codigo"])){ 
       $sql  .= $virgula." h01_codigo = $this->h01_codigo ";
       $virgula = ",";
       if(trim($this->h01_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo do Historico nao Informado.";
         $this->erro_campo = "h01_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h01_cgmentid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h01_cgmentid"])){ 
       $sql  .= $virgula." h01_cgmentid = $this->h01_cgmentid ";
       $virgula = ",";
       if(trim($this->h01_cgmentid) == null ){ 
         $this->erro_sql = " Campo Entidade nao Informado.";
         $this->erro_campo = "h01_cgmentid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h01_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h01_descr"])){ 
       $sql  .= $virgula." h01_descr = '$this->h01_descr' ";
       $virgula = ",";
       if(trim($this->h01_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do Histórico nao Informado.";
         $this->erro_campo = "h01_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h01_detalh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h01_detalh"])){ 
       $sql  .= $virgula." h01_detalh = '$this->h01_detalh' ";
       $virgula = ",";
     }
     if(trim($this->h01_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h01_codtipo"])){ 
       $sql  .= $virgula." h01_codtipo = $this->h01_codtipo ";
       $virgula = ",";
       if(trim($this->h01_codtipo) == null ){ 
         $this->erro_sql = " Campo Tipo do Curso nao Informado.";
         $this->erro_campo = "h01_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h01_cargahor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h01_cargahor"])){ 
       $sql  .= $virgula." h01_cargahor = $this->h01_cargahor ";
       $virgula = ",";
       if(trim($this->h01_cargahor) == null ){ 
         $this->erro_sql = " Campo Carga Horária nao Informado.";
         $this->erro_campo = "h01_cargahor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h01_codigo!=null){
       $sql .= " h01_codigo = $this->h01_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h01_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4481,'$this->h01_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h01_codigo"]))
           $resac = db_query("insert into db_acount values($acount,593,4481,'".AddSlashes(pg_result($resaco,$conresaco,'h01_codigo'))."','$this->h01_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h01_cgmentid"]))
           $resac = db_query("insert into db_acount values($acount,593,3868,'".AddSlashes(pg_result($resaco,$conresaco,'h01_cgmentid'))."','$this->h01_cgmentid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h01_descr"]))
           $resac = db_query("insert into db_acount values($acount,593,4482,'".AddSlashes(pg_result($resaco,$conresaco,'h01_descr'))."','$this->h01_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h01_detalh"]))
           $resac = db_query("insert into db_acount values($acount,593,4483,'".AddSlashes(pg_result($resaco,$conresaco,'h01_detalh'))."','$this->h01_detalh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h01_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,593,9674,'".AddSlashes(pg_result($resaco,$conresaco,'h01_codtipo'))."','$this->h01_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h01_cargahor"]))
           $resac = db_query("insert into db_acount values($acount,593,9672,'".AddSlashes(pg_result($resaco,$conresaco,'h01_cargahor'))."','$this->h01_cargahor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem a tabela de historicos de ocorrencias no cu nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem a tabela de historicos de ocorrencias no cu nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h01_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h01_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4481,'$h01_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,593,4481,'','".AddSlashes(pg_result($resaco,$iresaco,'h01_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,593,3868,'','".AddSlashes(pg_result($resaco,$iresaco,'h01_cgmentid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,593,4482,'','".AddSlashes(pg_result($resaco,$iresaco,'h01_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,593,4483,'','".AddSlashes(pg_result($resaco,$iresaco,'h01_detalh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,593,9674,'','".AddSlashes(pg_result($resaco,$iresaco,'h01_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,593,9672,'','".AddSlashes(pg_result($resaco,$iresaco,'h01_cargahor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabcurri
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h01_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h01_codigo = $h01_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contem a tabela de historicos de ocorrencias no cu nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h01_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Contem a tabela de historicos de ocorrencias no cu nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h01_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h01_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tabcurri";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabcurri ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = tabcurri.h01_cgmentid";
     $sql .= "      inner join tabcurritipo  on  tabcurritipo.h02_codigo = tabcurri.h01_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($h01_codigo!=null ){
         $sql2 .= " where tabcurri.h01_codigo = $h01_codigo "; 
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
   function sql_query_file ( $h01_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tabcurri ";
     $sql2 = "";
     if($dbwhere==""){
       if($h01_codigo!=null ){
         $sql2 .= " where tabcurri.h01_codigo = $h01_codigo "; 
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