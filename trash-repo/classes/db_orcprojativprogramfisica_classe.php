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
//CLASSE DA ENTIDADE orcprojativprogramfisica
class cl_orcprojativprogramfisica { 
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
   var $o28_sequencial = 0; 
   var $o28_orcprojativ = 0; 
   var $o28_anousu = 0; 
   var $o28_anoref = 0; 
   var $o28_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o28_sequencial = int4 = Sequencial 
                 o28_orcprojativ = int4 = Ação 
                 o28_anousu = int4 = Anousu 
                 o28_anoref = int4 = Ano de Referência 
                 o28_valor = float4 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_orcprojativprogramfisica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprojativprogramfisica"); 
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
       $this->o28_sequencial = ($this->o28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o28_sequencial"]:$this->o28_sequencial);
       $this->o28_orcprojativ = ($this->o28_orcprojativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o28_orcprojativ"]:$this->o28_orcprojativ);
       $this->o28_anousu = ($this->o28_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o28_anousu"]:$this->o28_anousu);
       $this->o28_anoref = ($this->o28_anoref == ""?@$GLOBALS["HTTP_POST_VARS"]["o28_anoref"]:$this->o28_anoref);
       $this->o28_valor = ($this->o28_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o28_valor"]:$this->o28_valor);
     }else{
       $this->o28_sequencial = ($this->o28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o28_sequencial"]:$this->o28_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o28_sequencial){ 
      $this->atualizacampos();
     if($this->o28_orcprojativ == null ){ 
       $this->erro_sql = " Campo Ação nao Informado.";
       $this->erro_campo = "o28_orcprojativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o28_anousu == null ){ 
       $this->erro_sql = " Campo Anousu nao Informado.";
       $this->erro_campo = "o28_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o28_anoref == null ){ 
       $this->erro_sql = " Campo Ano de Referência nao Informado.";
       $this->erro_campo = "o28_anoref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o28_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "o28_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o28_sequencial == "" || $o28_sequencial == null ){
       $result = db_query("select nextval('orcprojativprogramfisica_o28_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcprojativprogramfisica_o28_sequencial_seq do campo: o28_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o28_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcprojativprogramfisica_o28_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o28_sequencial)){
         $this->erro_sql = " Campo o28_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o28_sequencial = $o28_sequencial; 
       }
     }
     if(($this->o28_sequencial == null) || ($this->o28_sequencial == "") ){ 
       $this->erro_sql = " Campo o28_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprojativprogramfisica(
                                       o28_sequencial 
                                      ,o28_orcprojativ 
                                      ,o28_anousu 
                                      ,o28_anoref 
                                      ,o28_valor 
                       )
                values (
                                $this->o28_sequencial 
                               ,$this->o28_orcprojativ 
                               ,$this->o28_anousu 
                               ,$this->o28_anoref 
                               ,$this->o28_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programação Física por Ação ($this->o28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programação Física por Ação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programação Física por Ação ($this->o28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o28_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o28_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13739,'$this->o28_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2404,13739,'','".AddSlashes(pg_result($resaco,0,'o28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2404,13740,'','".AddSlashes(pg_result($resaco,0,'o28_orcprojativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2404,13741,'','".AddSlashes(pg_result($resaco,0,'o28_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2404,13742,'','".AddSlashes(pg_result($resaco,0,'o28_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2404,13743,'','".AddSlashes(pg_result($resaco,0,'o28_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o28_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcprojativprogramfisica set ";
     $virgula = "";
     if(trim($this->o28_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o28_sequencial"])){ 
       $sql  .= $virgula." o28_sequencial = $this->o28_sequencial ";
       $virgula = ",";
       if(trim($this->o28_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o28_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o28_orcprojativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o28_orcprojativ"])){ 
       $sql  .= $virgula." o28_orcprojativ = $this->o28_orcprojativ ";
       $virgula = ",";
       if(trim($this->o28_orcprojativ) == null ){ 
         $this->erro_sql = " Campo Ação nao Informado.";
         $this->erro_campo = "o28_orcprojativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o28_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o28_anousu"])){ 
       $sql  .= $virgula." o28_anousu = $this->o28_anousu ";
       $virgula = ",";
       if(trim($this->o28_anousu) == null ){ 
         $this->erro_sql = " Campo Anousu nao Informado.";
         $this->erro_campo = "o28_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o28_anoref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o28_anoref"])){ 
       $sql  .= $virgula." o28_anoref = $this->o28_anoref ";
       $virgula = ",";
       if(trim($this->o28_anoref) == null ){ 
         $this->erro_sql = " Campo Ano de Referência nao Informado.";
         $this->erro_campo = "o28_anoref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o28_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o28_valor"])){ 
       $sql  .= $virgula." o28_valor = $this->o28_valor ";
       $virgula = ",";
       if(trim($this->o28_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "o28_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o28_sequencial!=null){
       $sql .= " o28_sequencial = $this->o28_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o28_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13739,'$this->o28_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o28_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2404,13739,'".AddSlashes(pg_result($resaco,$conresaco,'o28_sequencial'))."','$this->o28_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o28_orcprojativ"]))
           $resac = db_query("insert into db_acount values($acount,2404,13740,'".AddSlashes(pg_result($resaco,$conresaco,'o28_orcprojativ'))."','$this->o28_orcprojativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o28_anousu"]))
           $resac = db_query("insert into db_acount values($acount,2404,13741,'".AddSlashes(pg_result($resaco,$conresaco,'o28_anousu'))."','$this->o28_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o28_anoref"]))
           $resac = db_query("insert into db_acount values($acount,2404,13742,'".AddSlashes(pg_result($resaco,$conresaco,'o28_anoref'))."','$this->o28_anoref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o28_valor"]))
           $resac = db_query("insert into db_acount values($acount,2404,13743,'".AddSlashes(pg_result($resaco,$conresaco,'o28_valor'))."','$this->o28_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Física por Ação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programação Física por Ação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o28_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o28_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13739,'$o28_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2404,13739,'','".AddSlashes(pg_result($resaco,$iresaco,'o28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2404,13740,'','".AddSlashes(pg_result($resaco,$iresaco,'o28_orcprojativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2404,13741,'','".AddSlashes(pg_result($resaco,$iresaco,'o28_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2404,13742,'','".AddSlashes(pg_result($resaco,$iresaco,'o28_anoref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2404,13743,'','".AddSlashes(pg_result($resaco,$iresaco,'o28_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprojativprogramfisica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o28_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o28_sequencial = $o28_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programação Física por Ação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programação Física por Ação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o28_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprojativprogramfisica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojativprogramfisica ";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu    = orcprojativprogramfisica.o28_anousu 
     									   and  orcprojativ.o55_projativ  = orcprojativprogramfisica.o28_orcprojativ";
     $sql .= "      inner join db_config    on  db_config.codigo		  = orcprojativ.o55_instit";
     $sql .= "      inner join orcproduto   on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql2 = "";
     if($dbwhere==""){
       if($o28_sequencial!=null ){
         $sql2 .= " where orcprojativprogramfisica.o28_sequencial = $o28_sequencial "; 
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
   function sql_query_file ( $o28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprojativprogramfisica ";
     $sql2 = "";
     if($dbwhere==""){
       if($o28_sequencial!=null ){
         $sql2 .= " where orcprojativprogramfisica.o28_sequencial = $o28_sequencial "; 
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