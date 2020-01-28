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
//CLASSE DA ENTIDADE ppaintegracaodespesa
class cl_ppaintegracaodespesa { 
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
   var $o121_sequencial = 0; 
   var $o121_ppaintegracao = 0; 
   var $o121_coddot = 0; 
   var $o121_anousu = 0; 
   var $o121_ppaestimativadespesa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o121_sequencial = int4 = Código Sequencial 
                 o121_ppaintegracao = int4 = Código da Integração 
                 o121_coddot = int4 = Código da Dotação 
                 o121_anousu = int4 = Ano da Dotação 
                 o121_ppaestimativadespesa = int4 = Código da Estimativa 
                 ";
   //funcao construtor da classe 
   function cl_ppaintegracaodespesa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaintegracaodespesa"); 
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
       $this->o121_sequencial = ($this->o121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o121_sequencial"]:$this->o121_sequencial);
       $this->o121_ppaintegracao = ($this->o121_ppaintegracao == ""?@$GLOBALS["HTTP_POST_VARS"]["o121_ppaintegracao"]:$this->o121_ppaintegracao);
       $this->o121_coddot = ($this->o121_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o121_coddot"]:$this->o121_coddot);
       $this->o121_anousu = ($this->o121_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o121_anousu"]:$this->o121_anousu);
       $this->o121_ppaestimativadespesa = ($this->o121_ppaestimativadespesa == ""?@$GLOBALS["HTTP_POST_VARS"]["o121_ppaestimativadespesa"]:$this->o121_ppaestimativadespesa);
     }else{
       $this->o121_sequencial = ($this->o121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o121_sequencial"]:$this->o121_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o121_sequencial){ 
      $this->atualizacampos();
     if($this->o121_ppaintegracao == null ){ 
       $this->erro_sql = " Campo Código da Integração nao Informado.";
       $this->erro_campo = "o121_ppaintegracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o121_coddot == null ){ 
       $this->erro_sql = " Campo Código da Dotação nao Informado.";
       $this->erro_campo = "o121_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o121_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Dotação nao Informado.";
       $this->erro_campo = "o121_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o121_ppaestimativadespesa == null ){ 
       $this->erro_sql = " Campo Código da Estimativa nao Informado.";
       $this->erro_campo = "o121_ppaestimativadespesa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o121_sequencial == "" || $o121_sequencial == null ){
       $result = db_query("select nextval('ppaintegracaodespesa_o121_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaintegracaodespesa_o121_sequencial_seq do campo: o121_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o121_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ppaintegracaodespesa_o121_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o121_sequencial)){
         $this->erro_sql = " Campo o121_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o121_sequencial = $o121_sequencial; 
       }
     }
     if(($this->o121_sequencial == null) || ($this->o121_sequencial == "") ){ 
       $this->erro_sql = " Campo o121_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaintegracaodespesa(
                                       o121_sequencial 
                                      ,o121_ppaintegracao 
                                      ,o121_coddot 
                                      ,o121_anousu 
                                      ,o121_ppaestimativadespesa 
                       )
                values (
                                $this->o121_sequencial 
                               ,$this->o121_ppaintegracao 
                               ,$this->o121_coddot 
                               ,$this->o121_anousu 
                               ,$this->o121_ppaestimativadespesa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Integracao da despesa (dotacoes) do ppa ($this->o121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Integracao da despesa (dotacoes) do ppa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Integracao da despesa (dotacoes) do ppa ($this->o121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o121_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o121_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14510,'$this->o121_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2557,14510,'','".AddSlashes(pg_result($resaco,0,'o121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2557,14511,'','".AddSlashes(pg_result($resaco,0,'o121_ppaintegracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2557,14512,'','".AddSlashes(pg_result($resaco,0,'o121_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2557,14513,'','".AddSlashes(pg_result($resaco,0,'o121_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2557,14514,'','".AddSlashes(pg_result($resaco,0,'o121_ppaestimativadespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o121_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ppaintegracaodespesa set ";
     $virgula = "";
     if(trim($this->o121_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o121_sequencial"])){ 
       $sql  .= $virgula." o121_sequencial = $this->o121_sequencial ";
       $virgula = ",";
       if(trim($this->o121_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o121_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o121_ppaintegracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o121_ppaintegracao"])){ 
       $sql  .= $virgula." o121_ppaintegracao = $this->o121_ppaintegracao ";
       $virgula = ",";
       if(trim($this->o121_ppaintegracao) == null ){ 
         $this->erro_sql = " Campo Código da Integração nao Informado.";
         $this->erro_campo = "o121_ppaintegracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o121_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o121_coddot"])){ 
       $sql  .= $virgula." o121_coddot = $this->o121_coddot ";
       $virgula = ",";
       if(trim($this->o121_coddot) == null ){ 
         $this->erro_sql = " Campo Código da Dotação nao Informado.";
         $this->erro_campo = "o121_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o121_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o121_anousu"])){ 
       $sql  .= $virgula." o121_anousu = $this->o121_anousu ";
       $virgula = ",";
       if(trim($this->o121_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Dotação nao Informado.";
         $this->erro_campo = "o121_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o121_ppaestimativadespesa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o121_ppaestimativadespesa"])){ 
       $sql  .= $virgula." o121_ppaestimativadespesa = $this->o121_ppaestimativadespesa ";
       $virgula = ",";
       if(trim($this->o121_ppaestimativadespesa) == null ){ 
         $this->erro_sql = " Campo Código da Estimativa nao Informado.";
         $this->erro_campo = "o121_ppaestimativadespesa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o121_sequencial!=null){
       $sql .= " o121_sequencial = $this->o121_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o121_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14510,'$this->o121_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o121_sequencial"]) || $this->o121_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2557,14510,'".AddSlashes(pg_result($resaco,$conresaco,'o121_sequencial'))."','$this->o121_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o121_ppaintegracao"]) || $this->o121_ppaintegracao != "")
           $resac = db_query("insert into db_acount values($acount,2557,14511,'".AddSlashes(pg_result($resaco,$conresaco,'o121_ppaintegracao'))."','$this->o121_ppaintegracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o121_coddot"]) || $this->o121_coddot != "")
           $resac = db_query("insert into db_acount values($acount,2557,14512,'".AddSlashes(pg_result($resaco,$conresaco,'o121_coddot'))."','$this->o121_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o121_anousu"]) || $this->o121_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2557,14513,'".AddSlashes(pg_result($resaco,$conresaco,'o121_anousu'))."','$this->o121_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o121_ppaestimativadespesa"]) || $this->o121_ppaestimativadespesa != "")
           $resac = db_query("insert into db_acount values($acount,2557,14514,'".AddSlashes(pg_result($resaco,$conresaco,'o121_ppaestimativadespesa'))."','$this->o121_ppaestimativadespesa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integracao da despesa (dotacoes) do ppa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Integracao da despesa (dotacoes) do ppa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o121_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o121_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14510,'$o121_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2557,14510,'','".AddSlashes(pg_result($resaco,$iresaco,'o121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2557,14511,'','".AddSlashes(pg_result($resaco,$iresaco,'o121_ppaintegracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2557,14512,'','".AddSlashes(pg_result($resaco,$iresaco,'o121_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2557,14513,'','".AddSlashes(pg_result($resaco,$iresaco,'o121_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2557,14514,'','".AddSlashes(pg_result($resaco,$iresaco,'o121_ppaestimativadespesa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ppaintegracaodespesa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o121_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o121_sequencial = $o121_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integracao da despesa (dotacoes) do ppa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Integracao da despesa (dotacoes) do ppa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o121_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaintegracaodespesa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaintegracaodespesa ";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = ppaintegracaodespesa.o121_anousu and  orcdotacao.o58_coddot = ppaintegracaodespesa.o121_coddot";
     $sql .= "      inner join ppaestimativadespesa  on  ppaestimativadespesa.o07_sequencial = ppaintegracaodespesa.o121_ppaestimativadespesa";
     $sql .= "      inner join ppaintegracao  on  ppaintegracao.o123_sequencial = ppaintegracaodespesa.o121_ppaintegracao";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and  orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join ppaestimativa  as a on   a.o05_sequencial = ppaestimativadespesa.o07_ppaestimativa";
     $sql .= "      inner join ppadotacao  on  ppadotacao.o08_sequencial = ppaestimativadespesa.o07_coddot";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaintegracao.o123_idusuario";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaintegracao.o123_ppaversao";
     $sql2 = "";
     if($dbwhere==""){
       if($o121_sequencial!=null ){
         $sql2 .= " where ppaintegracaodespesa.o121_sequencial = $o121_sequencial "; 
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
   function sql_query_file ( $o121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaintegracaodespesa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o121_sequencial!=null ){
         $sql2 .= " where ppaintegracaodespesa.o121_sequencial = $o121_sequencial "; 
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