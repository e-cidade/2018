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

//MODULO: configuracoes
//CLASSE DA ENTIDADE orcparamseqcoluna
class cl_orcparamseqcoluna { 
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
   var $o115_sequencial = 0; 
   var $o115_anousu = 0; 
   var $o115_descricao = null; 
   var $o115_tipo = 0; 
   var $o115_valoresdefault = null; 
   var $o115_nomecoluna = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o115_sequencial = int4 = Código Sequencial 
                 o115_anousu = int4 = Ano 
                 o115_descricao = varchar(100) = Descrição 
                 o115_tipo = int4 = Tipo da coluna 
                 o115_valoresdefault = text = Valor Default 
                 o115_nomecoluna = text = Nome da Coluna 
                 ";
   //funcao construtor da classe 
   function cl_orcparamseqcoluna() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcparamseqcoluna"); 
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
       $this->o115_sequencial = ($this->o115_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_sequencial"]:$this->o115_sequencial);
       $this->o115_anousu = ($this->o115_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_anousu"]:$this->o115_anousu);
       $this->o115_descricao = ($this->o115_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_descricao"]:$this->o115_descricao);
       $this->o115_tipo = ($this->o115_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_tipo"]:$this->o115_tipo);
       $this->o115_valoresdefault = ($this->o115_valoresdefault == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_valoresdefault"]:$this->o115_valoresdefault);
       $this->o115_nomecoluna = ($this->o115_nomecoluna == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_nomecoluna"]:$this->o115_nomecoluna);
     }else{
       $this->o115_sequencial = ($this->o115_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o115_sequencial"]:$this->o115_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o115_sequencial){ 
      $this->atualizacampos();
     if($this->o115_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o115_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o115_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o115_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o115_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da coluna nao Informado.";
       $this->erro_campo = "o115_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o115_sequencial == "" || $o115_sequencial == null ){
       $result = db_query("select nextval('orcparamseqcoluna_o115_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqcoluna_o115_sequencial_seq do campo: o115_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o115_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqcoluna_o115_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o115_sequencial)){
         $this->erro_sql = " Campo o115_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o115_sequencial = $o115_sequencial; 
       }
     }
     if(($this->o115_sequencial == null) || ($this->o115_sequencial == "") ){ 
       $this->erro_sql = " Campo o115_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqcoluna(
                                       o115_sequencial 
                                      ,o115_anousu 
                                      ,o115_descricao 
                                      ,o115_tipo 
                                      ,o115_valoresdefault 
                                      ,o115_nomecoluna 
                       )
                values (
                                $this->o115_sequencial 
                               ,$this->o115_anousu 
                               ,'$this->o115_descricao' 
                               ,$this->o115_tipo 
                               ,'$this->o115_valoresdefault' 
                               ,'$this->o115_nomecoluna' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Colunas do relatorio ($this->o115_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Colunas do relatorio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Colunas do relatorio ($this->o115_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o115_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o115_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14112,'$this->o115_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2482,14112,'','".AddSlashes(pg_result($resaco,0,'o115_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2482,14115,'','".AddSlashes(pg_result($resaco,0,'o115_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2482,14116,'','".AddSlashes(pg_result($resaco,0,'o115_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2482,14117,'','".AddSlashes(pg_result($resaco,0,'o115_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2482,15566,'','".AddSlashes(pg_result($resaco,0,'o115_valoresdefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2482,17725,'','".AddSlashes(pg_result($resaco,0,'o115_nomecoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o115_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcparamseqcoluna set ";
     $virgula = "";
     if(trim($this->o115_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o115_sequencial"])){ 
       $sql  .= $virgula." o115_sequencial = $this->o115_sequencial ";
       $virgula = ",";
       if(trim($this->o115_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o115_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o115_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o115_anousu"])){ 
       $sql  .= $virgula." o115_anousu = $this->o115_anousu ";
       $virgula = ",";
       if(trim($this->o115_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o115_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o115_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o115_descricao"])){ 
       $sql  .= $virgula." o115_descricao = '$this->o115_descricao' ";
       $virgula = ",";
       if(trim($this->o115_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o115_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o115_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o115_tipo"])){ 
       $sql  .= $virgula." o115_tipo = $this->o115_tipo ";
       $virgula = ",";
       if(trim($this->o115_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da coluna nao Informado.";
         $this->erro_campo = "o115_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o115_valoresdefault)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o115_valoresdefault"])){ 
       $sql  .= $virgula." o115_valoresdefault = '$this->o115_valoresdefault' ";
       $virgula = ",";
     }
     if(trim($this->o115_nomecoluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o115_nomecoluna"])){ 
       $sql  .= $virgula." o115_nomecoluna = '$this->o115_nomecoluna' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o115_sequencial!=null){
       $sql .= " o115_sequencial = $this->o115_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o115_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14112,'$this->o115_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o115_sequencial"]) || $this->o115_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2482,14112,'".AddSlashes(pg_result($resaco,$conresaco,'o115_sequencial'))."','$this->o115_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o115_anousu"]) || $this->o115_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2482,14115,'".AddSlashes(pg_result($resaco,$conresaco,'o115_anousu'))."','$this->o115_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o115_descricao"]) || $this->o115_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2482,14116,'".AddSlashes(pg_result($resaco,$conresaco,'o115_descricao'))."','$this->o115_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o115_tipo"]) || $this->o115_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2482,14117,'".AddSlashes(pg_result($resaco,$conresaco,'o115_tipo'))."','$this->o115_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o115_valoresdefault"]) || $this->o115_valoresdefault != "")
           $resac = db_query("insert into db_acount values($acount,2482,15566,'".AddSlashes(pg_result($resaco,$conresaco,'o115_valoresdefault'))."','$this->o115_valoresdefault',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o115_nomecoluna"]) || $this->o115_nomecoluna != "")
           $resac = db_query("insert into db_acount values($acount,2482,17725,'".AddSlashes(pg_result($resaco,$conresaco,'o115_nomecoluna'))."','$this->o115_nomecoluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Colunas do relatorio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o115_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Colunas do relatorio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o115_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o115_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o115_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o115_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14112,'$o115_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2482,14112,'','".AddSlashes(pg_result($resaco,$iresaco,'o115_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2482,14115,'','".AddSlashes(pg_result($resaco,$iresaco,'o115_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2482,14116,'','".AddSlashes(pg_result($resaco,$iresaco,'o115_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2482,14117,'','".AddSlashes(pg_result($resaco,$iresaco,'o115_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2482,15566,'','".AddSlashes(pg_result($resaco,$iresaco,'o115_valoresdefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2482,17725,'','".AddSlashes(pg_result($resaco,$iresaco,'o115_nomecoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcparamseqcoluna
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o115_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o115_sequencial = $o115_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Colunas do relatorio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o115_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Colunas do relatorio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o115_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o115_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqcoluna";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqcoluna ";
     $sql2 = "";
     if($dbwhere==""){
       if($o115_sequencial!=null ){
         $sql2 .= " where orcparamseqcoluna.o115_sequencial = $o115_sequencial "; 
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
   function sql_query_file ( $o115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcparamseqcoluna ";
     $sql2 = "";
     if($dbwhere==""){
       if($o115_sequencial!=null ){
         $sql2 .= " where orcparamseqcoluna.o115_sequencial = $o115_sequencial "; 
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