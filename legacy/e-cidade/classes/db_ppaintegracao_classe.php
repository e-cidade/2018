<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE ppaintegracao
class cl_ppaintegracao { 
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
   var $o123_sequencial = 0; 
   var $o123_idusuario = 0; 
   var $o123_ppaversao = 0; 
   var $o123_situacao = 0; 
   var $o123_ano = 0; 
   var $o123_data_dia = null; 
   var $o123_data_mes = null; 
   var $o123_data_ano = null; 
   var $o123_data = null; 
   var $o123_instit = 0; 
   var $o123_tipointegracao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o123_sequencial = int4 = Código Sequencial 
                 o123_idusuario = int4 = Código do Usuário 
                 o123_ppaversao = int4 = Versao Homologada 
                 o123_situacao = int4 = Situação 
                 o123_ano = int4 = Ano 
                 o123_data = date = Data Processamento 
                 o123_instit = int4 = Instituição 
                 o123_tipointegracao = int4 = Tipo da Integração 
                 ";
   //funcao construtor da classe 
   function cl_ppaintegracao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaintegracao"); 
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
       $this->o123_sequencial = ($this->o123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_sequencial"]:$this->o123_sequencial);
       $this->o123_idusuario = ($this->o123_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_idusuario"]:$this->o123_idusuario);
       $this->o123_ppaversao = ($this->o123_ppaversao == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_ppaversao"]:$this->o123_ppaversao);
       $this->o123_situacao = ($this->o123_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_situacao"]:$this->o123_situacao);
       $this->o123_ano = ($this->o123_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_ano"]:$this->o123_ano);
       if($this->o123_data == ""){
         $this->o123_data_dia = ($this->o123_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_data_dia"]:$this->o123_data_dia);
         $this->o123_data_mes = ($this->o123_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_data_mes"]:$this->o123_data_mes);
         $this->o123_data_ano = ($this->o123_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_data_ano"]:$this->o123_data_ano);
         if($this->o123_data_dia != ""){
            $this->o123_data = $this->o123_data_ano."-".$this->o123_data_mes."-".$this->o123_data_dia;
         }
       }
       $this->o123_instit = ($this->o123_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_instit"]:$this->o123_instit);
       $this->o123_tipointegracao = ($this->o123_tipointegracao == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_tipointegracao"]:$this->o123_tipointegracao);
     }else{
       $this->o123_sequencial = ($this->o123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o123_sequencial"]:$this->o123_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o123_sequencial){ 
      $this->atualizacampos();
     if($this->o123_idusuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "o123_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o123_ppaversao == null ){ 
       $this->erro_sql = " Campo Versao Homologada nao Informado.";
       $this->erro_campo = "o123_ppaversao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o123_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "o123_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o123_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o123_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o123_data == null ){ 
       $this->erro_sql = " Campo Data Processamento nao Informado.";
       $this->erro_campo = "o123_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o123_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "o123_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o123_tipointegracao == null ){ 
       $this->erro_sql = " Campo Tipo da Integração nao Informado.";
       $this->erro_campo = "o123_tipointegracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o123_sequencial == "" || $o123_sequencial == null ){
       $result = db_query("select nextval('ppaintegracao_o123_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaintegracao_o123_sequencial_seq do campo: o123_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o123_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ppaintegracao_o123_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o123_sequencial)){
         $this->erro_sql = " Campo o123_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o123_sequencial = $o123_sequencial; 
       }
     }
     if(($this->o123_sequencial == null) || ($this->o123_sequencial == "") ){ 
       $this->erro_sql = " Campo o123_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaintegracao(
                                       o123_sequencial 
                                      ,o123_idusuario 
                                      ,o123_ppaversao 
                                      ,o123_situacao 
                                      ,o123_ano 
                                      ,o123_data 
                                      ,o123_instit 
                                      ,o123_tipointegracao 
                       )
                values (
                                $this->o123_sequencial 
                               ,$this->o123_idusuario 
                               ,$this->o123_ppaversao 
                               ,$this->o123_situacao 
                               ,$this->o123_ano 
                               ,".($this->o123_data == "null" || $this->o123_data == ""?"null":"'".$this->o123_data."'")." 
                               ,$this->o123_instit 
                               ,$this->o123_tipointegracao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Integracao do ppa com orçamentp ($this->o123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Integracao do ppa com orçamentp já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Integracao do ppa com orçamentp ($this->o123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o123_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o123_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14500,'$this->o123_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2555,14500,'','".AddSlashes(pg_result($resaco,0,'o123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,14501,'','".AddSlashes(pg_result($resaco,0,'o123_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,14505,'','".AddSlashes(pg_result($resaco,0,'o123_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,14502,'','".AddSlashes(pg_result($resaco,0,'o123_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,14503,'','".AddSlashes(pg_result($resaco,0,'o123_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,14504,'','".AddSlashes(pg_result($resaco,0,'o123_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,14521,'','".AddSlashes(pg_result($resaco,0,'o123_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2555,17681,'','".AddSlashes(pg_result($resaco,0,'o123_tipointegracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o123_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ppaintegracao set ";
     $virgula = "";
     if(trim($this->o123_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_sequencial"])){ 
       $sql  .= $virgula." o123_sequencial = $this->o123_sequencial ";
       $virgula = ",";
       if(trim($this->o123_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o123_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o123_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_idusuario"])){ 
       $sql  .= $virgula." o123_idusuario = $this->o123_idusuario ";
       $virgula = ",";
       if(trim($this->o123_idusuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "o123_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o123_ppaversao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_ppaversao"])){ 
       $sql  .= $virgula." o123_ppaversao = $this->o123_ppaversao ";
       $virgula = ",";
       if(trim($this->o123_ppaversao) == null ){ 
         $this->erro_sql = " Campo Versao Homologada nao Informado.";
         $this->erro_campo = "o123_ppaversao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o123_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_situacao"])){ 
       $sql  .= $virgula." o123_situacao = $this->o123_situacao ";
       $virgula = ",";
       if(trim($this->o123_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "o123_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o123_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_ano"])){ 
       $sql  .= $virgula." o123_ano = $this->o123_ano ";
       $virgula = ",";
       if(trim($this->o123_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o123_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o123_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o123_data_dia"] !="") ){ 
       $sql  .= $virgula." o123_data = '$this->o123_data' ";
       $virgula = ",";
       if(trim($this->o123_data) == null ){ 
         $this->erro_sql = " Campo Data Processamento nao Informado.";
         $this->erro_campo = "o123_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o123_data_dia"])){ 
         $sql  .= $virgula." o123_data = null ";
         $virgula = ",";
         if(trim($this->o123_data) == null ){ 
           $this->erro_sql = " Campo Data Processamento nao Informado.";
           $this->erro_campo = "o123_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o123_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_instit"])){ 
       $sql  .= $virgula." o123_instit = $this->o123_instit ";
       $virgula = ",";
       if(trim($this->o123_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o123_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o123_tipointegracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o123_tipointegracao"])){ 
       $sql  .= $virgula." o123_tipointegracao = $this->o123_tipointegracao ";
       $virgula = ",";
       if(trim($this->o123_tipointegracao) == null ){ 
         $this->erro_sql = " Campo Tipo da Integração nao Informado.";
         $this->erro_campo = "o123_tipointegracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o123_sequencial!=null){
       $sql .= " o123_sequencial = $this->o123_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o123_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14500,'$this->o123_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_sequencial"]) || $this->o123_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2555,14500,'".AddSlashes(pg_result($resaco,$conresaco,'o123_sequencial'))."','$this->o123_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_idusuario"]) || $this->o123_idusuario != "")
           $resac = db_query("insert into db_acount values($acount,2555,14501,'".AddSlashes(pg_result($resaco,$conresaco,'o123_idusuario'))."','$this->o123_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_ppaversao"]) || $this->o123_ppaversao != "")
           $resac = db_query("insert into db_acount values($acount,2555,14505,'".AddSlashes(pg_result($resaco,$conresaco,'o123_ppaversao'))."','$this->o123_ppaversao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_situacao"]) || $this->o123_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2555,14502,'".AddSlashes(pg_result($resaco,$conresaco,'o123_situacao'))."','$this->o123_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_ano"]) || $this->o123_ano != "")
           $resac = db_query("insert into db_acount values($acount,2555,14503,'".AddSlashes(pg_result($resaco,$conresaco,'o123_ano'))."','$this->o123_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_data"]) || $this->o123_data != "")
           $resac = db_query("insert into db_acount values($acount,2555,14504,'".AddSlashes(pg_result($resaco,$conresaco,'o123_data'))."','$this->o123_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_instit"]) || $this->o123_instit != "")
           $resac = db_query("insert into db_acount values($acount,2555,14521,'".AddSlashes(pg_result($resaco,$conresaco,'o123_instit'))."','$this->o123_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o123_tipointegracao"]) || $this->o123_tipointegracao != "")
           $resac = db_query("insert into db_acount values($acount,2555,17681,'".AddSlashes(pg_result($resaco,$conresaco,'o123_tipointegracao'))."','$this->o123_tipointegracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integracao do ppa com orçamentp nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Integracao do ppa com orçamentp nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o123_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o123_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14500,'$o123_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2555,14500,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,14501,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,14505,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,14502,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,14503,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,14504,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,14521,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2555,17681,'','".AddSlashes(pg_result($resaco,$iresaco,'o123_tipointegracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ppaintegracao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o123_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o123_sequencial = $o123_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integracao do ppa com orçamentp nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Integracao do ppa com orçamentp nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o123_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaintegracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaintegracao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaintegracao.o123_idusuario";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaintegracao.o123_ppaversao";
     $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql2 = "";
     if($dbwhere==""){
       if($o123_sequencial!=null ){
         $sql2 .= " where ppaintegracao.o123_sequencial = $o123_sequencial "; 
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
   function sql_query_file ( $o123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaintegracao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o123_sequencial!=null ){
         $sql2 .= " where ppaintegracao.o123_sequencial = $o123_sequencial "; 
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
   * Busca os dados da ppaintegracao juntamente com os da ppaintegracaodespesa
   *
   * @param unknown_type $o123_sequencial
   * @param unknown_type $campos
   * @param unknown_type $ordem
   * @param unknown_type $dbwhere
   * @return unknown
   */
  function sql_query_versaoppa ( $o123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select distinct ";
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
     $sql .= " from ppaintegracao ";
     $sql .= "      inner join ppaintegracaodespesa on ppaintegracaodespesa.o121_ppaintegracao = ppaintegracao.o123_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($o123_sequencial!=null ){
         $sql2 .= " where ppaintegracao.o123_sequencial = $o123_sequencial "; 
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