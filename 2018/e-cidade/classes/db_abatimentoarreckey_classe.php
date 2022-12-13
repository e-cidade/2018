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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentoarreckey
class cl_abatimentoarreckey { 
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
   var $k128_sequencial = 0; 
   var $k128_arreckey = 0; 
   var $k128_abatimento = 0; 
   var $k128_valorabatido = 0; 
   var $k128_correcao = 0; 
   var $k128_juros = 0; 
   var $k128_multa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k128_sequencial = int4 = Sequencial 
                 k128_arreckey = int4 = Arreckey 
                 k128_abatimento = int4 = Abatimento 
                 k128_valorabatido = numeric(15,2) = Valor Abatido 
                 k128_correcao = numeric(15) = Valor Correção 
                 k128_juros = numeric(15) = Valor Juros 
                 k128_multa = numeric(15) = Valor Multa 
                 ";
   //funcao construtor da classe 
   function cl_abatimentoarreckey() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentoarreckey"); 
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
       $this->k128_sequencial = ($this->k128_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_sequencial"]:$this->k128_sequencial);
       $this->k128_arreckey = ($this->k128_arreckey == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_arreckey"]:$this->k128_arreckey);
       $this->k128_abatimento = ($this->k128_abatimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_abatimento"]:$this->k128_abatimento);
       $this->k128_valorabatido = ($this->k128_valorabatido == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_valorabatido"]:$this->k128_valorabatido);
       $this->k128_correcao = ($this->k128_correcao == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_correcao"]:$this->k128_correcao);
       $this->k128_juros = ($this->k128_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_juros"]:$this->k128_juros);
       $this->k128_multa = ($this->k128_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_multa"]:$this->k128_multa);
     }else{
       $this->k128_sequencial = ($this->k128_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k128_sequencial"]:$this->k128_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k128_sequencial){ 
      $this->atualizacampos();
     if($this->k128_arreckey == null ){ 
       $this->erro_sql = " Campo Arreckey nao Informado.";
       $this->erro_campo = "k128_arreckey";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k128_abatimento == null ){ 
       $this->erro_sql = " Campo Abatimento nao Informado.";
       $this->erro_campo = "k128_abatimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k128_valorabatido == null ){ 
       $this->erro_sql = " Campo Valor Abatido nao Informado.";
       $this->erro_campo = "k128_valorabatido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k128_correcao == null ){ 
       $this->k128_correcao = "0";
     }
     if($this->k128_juros == null ){ 
       $this->k128_juros = "0";
     }
     if($this->k128_multa == null ){ 
       $this->k128_multa = "0";
     }
     if($k128_sequencial == "" || $k128_sequencial == null ){
       $result = db_query("select nextval('abatimentoarreckey_k128_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: abatimentoarreckey_k128_sequencial_seq do campo: k128_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k128_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from abatimentoarreckey_k128_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k128_sequencial)){
         $this->erro_sql = " Campo k128_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k128_sequencial = $k128_sequencial; 
       }
     }
     if(($this->k128_sequencial == null) || ($this->k128_sequencial == "") ){ 
       $this->erro_sql = " Campo k128_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentoarreckey(
                                       k128_sequencial 
                                      ,k128_arreckey 
                                      ,k128_abatimento 
                                      ,k128_valorabatido 
                                      ,k128_correcao 
                                      ,k128_juros 
                                      ,k128_multa 
                       )
                values (
                                $this->k128_sequencial 
                               ,$this->k128_arreckey 
                               ,$this->k128_abatimento 
                               ,$this->k128_valorabatido 
                               ,$this->k128_correcao 
                               ,$this->k128_juros 
                               ,$this->k128_multa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ligaçao entre os Débitos e Abatimentos ($this->k128_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ligaçao entre os Débitos e Abatimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ligaçao entre os Débitos e Abatimentos ($this->k128_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k128_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k128_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18070,'$this->k128_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3193,18070,'','".AddSlashes(pg_result($resaco,0,'k128_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3193,18071,'','".AddSlashes(pg_result($resaco,0,'k128_arreckey'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3193,18072,'','".AddSlashes(pg_result($resaco,0,'k128_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3193,18073,'','".AddSlashes(pg_result($resaco,0,'k128_valorabatido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3193,20138,'','".AddSlashes(pg_result($resaco,0,'k128_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3193,20139,'','".AddSlashes(pg_result($resaco,0,'k128_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3193,20140,'','".AddSlashes(pg_result($resaco,0,'k128_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k128_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update abatimentoarreckey set ";
     $virgula = "";
     if(trim($this->k128_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_sequencial"])){ 
       $sql  .= $virgula." k128_sequencial = $this->k128_sequencial ";
       $virgula = ",";
       if(trim($this->k128_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k128_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k128_arreckey)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_arreckey"])){ 
       $sql  .= $virgula." k128_arreckey = $this->k128_arreckey ";
       $virgula = ",";
       if(trim($this->k128_arreckey) == null ){ 
         $this->erro_sql = " Campo Arreckey nao Informado.";
         $this->erro_campo = "k128_arreckey";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k128_abatimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_abatimento"])){ 
       $sql  .= $virgula." k128_abatimento = $this->k128_abatimento ";
       $virgula = ",";
       if(trim($this->k128_abatimento) == null ){ 
         $this->erro_sql = " Campo Abatimento nao Informado.";
         $this->erro_campo = "k128_abatimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k128_valorabatido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_valorabatido"])){ 
       $sql  .= $virgula." k128_valorabatido = $this->k128_valorabatido ";
       $virgula = ",";
       if(trim($this->k128_valorabatido) == null ){ 
         $this->erro_sql = " Campo Valor Abatido nao Informado.";
         $this->erro_campo = "k128_valorabatido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k128_correcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_correcao"])){ 
       $sql  .= $virgula." k128_correcao = $this->k128_correcao ";
       $virgula = ",";
     }
     if(trim($this->k128_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_juros"])){ 
       $sql  .= $virgula." k128_juros = $this->k128_juros ";
       $virgula = ",";
     }
     if(trim($this->k128_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k128_multa"])){ 
       $sql  .= $virgula." k128_multa = $this->k128_multa ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k128_sequencial!=null){
       $sql .= " k128_sequencial = $this->k128_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k128_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18070,'$this->k128_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_sequencial"]) || $this->k128_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3193,18070,'".AddSlashes(pg_result($resaco,$conresaco,'k128_sequencial'))."','$this->k128_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_arreckey"]) || $this->k128_arreckey != "")
             $resac = db_query("insert into db_acount values($acount,3193,18071,'".AddSlashes(pg_result($resaco,$conresaco,'k128_arreckey'))."','$this->k128_arreckey',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_abatimento"]) || $this->k128_abatimento != "")
             $resac = db_query("insert into db_acount values($acount,3193,18072,'".AddSlashes(pg_result($resaco,$conresaco,'k128_abatimento'))."','$this->k128_abatimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_valorabatido"]) || $this->k128_valorabatido != "")
             $resac = db_query("insert into db_acount values($acount,3193,18073,'".AddSlashes(pg_result($resaco,$conresaco,'k128_valorabatido'))."','$this->k128_valorabatido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_correcao"]) || $this->k128_correcao != "")
             $resac = db_query("insert into db_acount values($acount,3193,20138,'".AddSlashes(pg_result($resaco,$conresaco,'k128_correcao'))."','$this->k128_correcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_juros"]) || $this->k128_juros != "")
             $resac = db_query("insert into db_acount values($acount,3193,20139,'".AddSlashes(pg_result($resaco,$conresaco,'k128_juros'))."','$this->k128_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["k128_multa"]) || $this->k128_multa != "")
             $resac = db_query("insert into db_acount values($acount,3193,20140,'".AddSlashes(pg_result($resaco,$conresaco,'k128_multa'))."','$this->k128_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligaçao entre os Débitos e Abatimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k128_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligaçao entre os Débitos e Abatimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k128_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k128_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k128_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($k128_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18070,'$k128_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3193,18070,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3193,18071,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_arreckey'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3193,18072,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_abatimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3193,18073,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_valorabatido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3193,20138,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3193,20139,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3193,20140,'','".AddSlashes(pg_result($resaco,$iresaco,'k128_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from abatimentoarreckey
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k128_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k128_sequencial = $k128_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ligaçao entre os Débitos e Abatimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k128_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ligaçao entre os Débitos e Abatimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k128_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k128_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:abatimentoarreckey";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k128_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoarreckey ";
     $sql .= "      inner join arreckey            on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey";
     $sql .= "      inner join abatimento          on abatimento.k125_sequencial         = abatimentoarreckey.k128_abatimento";
     $sql .= "      inner join tabrec              on tabrec.k02_codigo                  = arreckey.k00_receit";
     $sql .= "      inner join arretipo            on arretipo.k00_tipo                  = arreckey.k00_tipo";
     $sql .= "      inner join db_config           on db_config.codigo                   = abatimento.k125_instit";
     $sql .= "      inner join db_usuarios         on db_usuarios.id_usuario             = abatimento.k125_usuario";
     $sql .= "      inner join tipoabatimento      on tipoabatimento.k126_sequencial     = abatimento.k125_tipoabatimento";
     $sql .= "      inner join abatimentosituacao  on abatimentosituacao.k165_sequencial = abatimento.k125_abatimentosituacao";
     $sql .= "       left join histcalc            on histcalc.k01_codigo                = arreckey.k00_hist";
     $sql2 = "";
     if($dbwhere==""){
       if($k128_sequencial!=null ){
         $sql2 .= " where abatimentoarreckey.k128_sequencial = $k128_sequencial "; 
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
   function sql_query_file ( $k128_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from abatimentoarreckey ";
     $sql2 = "";
     if($dbwhere==""){
       if($k128_sequencial!=null ){
         $sql2 .= " where abatimentoarreckey.k128_sequencial = $k128_sequencial "; 
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
   /**
   * Busca abatimentos  
   *
   * @param string $sCampos
   * @param string $sOrem
   * @param string $sWhere
   */
  function sql_query_buscaAbatimento($sCampos, $sOrdem, $sWhere) { 
                                                                                                                            
		$sSql  = "select {$sCampos}                                                                                             ";
    $sSql .= "from abatimentoarreckey                                                                                       ";
    $sSql .= "     inner join arreckey                     on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey   ";
    $sSql .= "     inner join histcalc                     on histcalc.k01_codigo                = arreckey.k00_hist                  ";
    $sSql .= "     inner join tabrec                       on tabrec.k02_codigo                  = arreckey.k00_receit                ";
    $sSql .= "     inner join arretipo                     on arretipo.k00_tipo                  = arreckey.k00_tipo                  ";
    $sSql .= "     inner join abatimento                   on abatimento.k125_sequencial         = abatimentoarreckey.k128_abatimento ";
    $sSql .= "     inner join db_config                    on db_config.codigo                   = abatimento.k125_instit             ";
    $sSql .= "     inner join db_usuarios                  on db_usuarios.id_usuario             = abatimento.k125_usuario            ";
    $sSql .= "     inner join tipoabatimento               on tipoabatimento.k126_sequencial     = abatimento.k125_tipoabatimento     ";
    $sSql .= "     inner join abatimentosituacao           on abatimentosituacao.k165_sequencial = abatimento.k125_abatimentosituacao ";
    $sSql .= "     left  join abatimentorecibo             on abatimentorecibo.k127_abatimento   = abatimento.k125_sequencial         ";
    $sSql .= "     left  join abatimentodisbanco           on abatimentodisbanco.k132_abatimento = abatimento.k125_sequencial         ";
    $sSql .= "     left  join arrecad                      on arrecad.k00_numpre                 = arreckey.k00_numpre                ";
    $sSql .= "                                            and arrecad.k00_numpar                 = arreckey.k00_numpar                ";
    $sSql .= "                                            and arrecad.k00_receit                 = arreckey.k00_receit                ";
    $sSql .= "     left  join arrepaga as arrepagarecibo   on arrepagarecibo.k00_numpre          = abatimentorecibo.k127_numprerecibo ";
    $sSql .= "                                            and arrepagarecibo.k00_receit          = arreckey.k00_receit                ";                                               
       

    $sSql .= "where {$sWhere} ";

    if ( !empty($sOrdem) ) {
      $sSql .= "order by {$sOrdem} ";
    }

    return $sSql;
  }
}
?>