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

//MODULO: Compras
//CLASSE DA ENTIDADE liberafornecedor
class cl_liberafornecedor { 
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
   var $pc82_sequencial = 0; 
   var $pc82_id_usuario = 0; 
   var $pc82_numcgm = 0; 
   var $pc82_dataini_dia = null; 
   var $pc82_dataini_mes = null; 
   var $pc82_dataini_ano = null; 
   var $pc82_dataini = null; 
   var $pc82_datafim_dia = null; 
   var $pc82_datafim_mes = null; 
   var $pc82_datafim_ano = null; 
   var $pc82_datafim = null; 
   var $pc82_obs = null; 
   var $pc82_data_dia = null; 
   var $pc82_data_mes = null; 
   var $pc82_data_ano = null; 
   var $pc82_data = null; 
   var $pc82_liberasol = 'f'; 
   var $pc82_liberaproc = 'f'; 
   var $pc82_liberaaut = 'f'; 
   var $pc82_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc82_sequencial = int4 = Código 
                 pc82_id_usuario = int4 = Usuário 
                 pc82_numcgm = int4 = CGM 
                 pc82_dataini = date = Data Inicial 
                 pc82_datafim = date = Data Fim 
                 pc82_obs = text = Observação 
                 pc82_data = date = Data 
                 pc82_liberasol = bool = Libera Solicitação 
                 pc82_liberaproc = bool = Libera Processo de Compras 
                 pc82_liberaaut = bool = Liberação Autorização de Empenho 
                 pc82_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_liberafornecedor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liberafornecedor"); 
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
       $this->pc82_sequencial = ($this->pc82_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_sequencial"]:$this->pc82_sequencial);
       $this->pc82_id_usuario = ($this->pc82_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_id_usuario"]:$this->pc82_id_usuario);
       $this->pc82_numcgm = ($this->pc82_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_numcgm"]:$this->pc82_numcgm);
       if($this->pc82_dataini == ""){
         $this->pc82_dataini_dia = ($this->pc82_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_dataini_dia"]:$this->pc82_dataini_dia);
         $this->pc82_dataini_mes = ($this->pc82_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_dataini_mes"]:$this->pc82_dataini_mes);
         $this->pc82_dataini_ano = ($this->pc82_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_dataini_ano"]:$this->pc82_dataini_ano);
         if($this->pc82_dataini_dia != ""){
            $this->pc82_dataini = $this->pc82_dataini_ano."-".$this->pc82_dataini_mes."-".$this->pc82_dataini_dia;
         }
       }
       if($this->pc82_datafim == ""){
         $this->pc82_datafim_dia = ($this->pc82_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_datafim_dia"]:$this->pc82_datafim_dia);
         $this->pc82_datafim_mes = ($this->pc82_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_datafim_mes"]:$this->pc82_datafim_mes);
         $this->pc82_datafim_ano = ($this->pc82_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_datafim_ano"]:$this->pc82_datafim_ano);
         if($this->pc82_datafim_dia != ""){
            $this->pc82_datafim = $this->pc82_datafim_ano."-".$this->pc82_datafim_mes."-".$this->pc82_datafim_dia;
         }
       }
       $this->pc82_obs = ($this->pc82_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_obs"]:$this->pc82_obs);
       if($this->pc82_data == ""){
         $this->pc82_data_dia = ($this->pc82_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_data_dia"]:$this->pc82_data_dia);
         $this->pc82_data_mes = ($this->pc82_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_data_mes"]:$this->pc82_data_mes);
         $this->pc82_data_ano = ($this->pc82_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_data_ano"]:$this->pc82_data_ano);
         if($this->pc82_data_dia != ""){
            $this->pc82_data = $this->pc82_data_ano."-".$this->pc82_data_mes."-".$this->pc82_data_dia;
         }
       }
       $this->pc82_liberasol = ($this->pc82_liberasol == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc82_liberasol"]:$this->pc82_liberasol);
       $this->pc82_liberaproc = ($this->pc82_liberaproc == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc82_liberaproc"]:$this->pc82_liberaproc);
       $this->pc82_liberaaut = ($this->pc82_liberaaut == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc82_liberaaut"]:$this->pc82_liberaaut);
       $this->pc82_ativo = ($this->pc82_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc82_ativo"]:$this->pc82_ativo);
     }else{
       $this->pc82_sequencial = ($this->pc82_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc82_sequencial"]:$this->pc82_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc82_sequencial){ 
      $this->atualizacampos();
     if($this->pc82_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "pc82_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc82_numcgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "pc82_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc82_dataini == null ){ 
       $this->pc82_dataini = "null";
     }
     if($this->pc82_datafim == null ){ 
       $this->pc82_datafim = "null";
     }
     if($this->pc82_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "pc82_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc82_liberasol == null ){ 
       $this->erro_sql = " Campo Libera Solicitação nao Informado.";
       $this->erro_campo = "pc82_liberasol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc82_liberaproc == null ){ 
       $this->erro_sql = " Campo Libera Processo de Compras nao Informado.";
       $this->erro_campo = "pc82_liberaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc82_liberaaut == null ){ 
       $this->erro_sql = " Campo Liberação Autorização de Empenho nao Informado.";
       $this->erro_campo = "pc82_liberaaut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc82_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "pc82_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc82_sequencial == "" || $pc82_sequencial == null ){
       $result = db_query("select nextval('liberafornecedor_pc82_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liberafornecedor_pc82_sequencial_seq do campo: pc82_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc82_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liberafornecedor_pc82_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc82_sequencial)){
         $this->erro_sql = " Campo pc82_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc82_sequencial = $pc82_sequencial; 
       }
     }
     if(($this->pc82_sequencial == null) || ($this->pc82_sequencial == "") ){ 
       $this->erro_sql = " Campo pc82_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liberafornecedor(
                                       pc82_sequencial 
                                      ,pc82_id_usuario 
                                      ,pc82_numcgm 
                                      ,pc82_dataini 
                                      ,pc82_datafim 
                                      ,pc82_obs 
                                      ,pc82_data 
                                      ,pc82_liberasol 
                                      ,pc82_liberaproc 
                                      ,pc82_liberaaut 
                                      ,pc82_ativo 
                       )
                values (
                                $this->pc82_sequencial 
                               ,$this->pc82_id_usuario 
                               ,$this->pc82_numcgm 
                               ,".($this->pc82_dataini == "null" || $this->pc82_dataini == ""?"null":"'".$this->pc82_dataini."'")." 
                               ,".($this->pc82_datafim == "null" || $this->pc82_datafim == ""?"null":"'".$this->pc82_datafim."'")." 
                               ,'$this->pc82_obs' 
                               ,".($this->pc82_data == "null" || $this->pc82_data == ""?"null":"'".$this->pc82_data."'")." 
                               ,'$this->pc82_liberasol' 
                               ,'$this->pc82_liberaproc' 
                               ,'$this->pc82_liberaaut' 
                               ,'$this->pc82_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Libera Fornecedor ($this->pc82_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Libera Fornecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Libera Fornecedor ($this->pc82_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc82_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc82_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15518,'$this->pc82_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2724,15518,'','".AddSlashes(pg_result($resaco,0,'pc82_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15519,'','".AddSlashes(pg_result($resaco,0,'pc82_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15520,'','".AddSlashes(pg_result($resaco,0,'pc82_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15521,'','".AddSlashes(pg_result($resaco,0,'pc82_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15522,'','".AddSlashes(pg_result($resaco,0,'pc82_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15523,'','".AddSlashes(pg_result($resaco,0,'pc82_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15524,'','".AddSlashes(pg_result($resaco,0,'pc82_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15525,'','".AddSlashes(pg_result($resaco,0,'pc82_liberasol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15526,'','".AddSlashes(pg_result($resaco,0,'pc82_liberaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15527,'','".AddSlashes(pg_result($resaco,0,'pc82_liberaaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2724,15528,'','".AddSlashes(pg_result($resaco,0,'pc82_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc82_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liberafornecedor set ";
     $virgula = "";
     if(trim($this->pc82_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_sequencial"])){ 
       $sql  .= $virgula." pc82_sequencial = $this->pc82_sequencial ";
       $virgula = ",";
       if(trim($this->pc82_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc82_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc82_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_id_usuario"])){ 
       $sql  .= $virgula." pc82_id_usuario = $this->pc82_id_usuario ";
       $virgula = ",";
       if(trim($this->pc82_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "pc82_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc82_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_numcgm"])){ 
       $sql  .= $virgula." pc82_numcgm = $this->pc82_numcgm ";
       $virgula = ",";
       if(trim($this->pc82_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "pc82_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc82_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc82_dataini_dia"] !="") ){ 
       $sql  .= $virgula." pc82_dataini = '$this->pc82_dataini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_dataini_dia"])){ 
         $sql  .= $virgula." pc82_dataini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc82_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc82_datafim_dia"] !="") ){ 
       $sql  .= $virgula." pc82_datafim = '$this->pc82_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_datafim_dia"])){ 
         $sql  .= $virgula." pc82_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc82_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_obs"])){ 
       $sql  .= $virgula." pc82_obs = '$this->pc82_obs' ";
       $virgula = ",";
     }
     if(trim($this->pc82_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc82_data_dia"] !="") ){ 
       $sql  .= $virgula." pc82_data = '$this->pc82_data' ";
       $virgula = ",";
       if(trim($this->pc82_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "pc82_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_data_dia"])){ 
         $sql  .= $virgula." pc82_data = null ";
         $virgula = ",";
         if(trim($this->pc82_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "pc82_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc82_liberasol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_liberasol"])){ 
       $sql  .= $virgula." pc82_liberasol = '$this->pc82_liberasol' ";
       $virgula = ",";
       if(trim($this->pc82_liberasol) == null ){ 
         $this->erro_sql = " Campo Libera Solicitação nao Informado.";
         $this->erro_campo = "pc82_liberasol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc82_liberaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_liberaproc"])){ 
       $sql  .= $virgula." pc82_liberaproc = '$this->pc82_liberaproc' ";
       $virgula = ",";
       if(trim($this->pc82_liberaproc) == null ){ 
         $this->erro_sql = " Campo Libera Processo de Compras nao Informado.";
         $this->erro_campo = "pc82_liberaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc82_liberaaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_liberaaut"])){ 
       $sql  .= $virgula." pc82_liberaaut = '$this->pc82_liberaaut' ";
       $virgula = ",";
       if(trim($this->pc82_liberaaut) == null ){ 
         $this->erro_sql = " Campo Liberação Autorização de Empenho nao Informado.";
         $this->erro_campo = "pc82_liberaaut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc82_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc82_ativo"])){ 
       $sql  .= $virgula." pc82_ativo = '$this->pc82_ativo' ";
       $virgula = ",";
       if(trim($this->pc82_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "pc82_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc82_sequencial!=null){
       $sql .= " pc82_sequencial = $this->pc82_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc82_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15518,'$this->pc82_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_sequencial"]) || $this->pc82_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2724,15518,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_sequencial'))."','$this->pc82_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_id_usuario"]) || $this->pc82_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2724,15519,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_id_usuario'))."','$this->pc82_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_numcgm"]) || $this->pc82_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2724,15520,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_numcgm'))."','$this->pc82_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_dataini"]) || $this->pc82_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2724,15521,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_dataini'))."','$this->pc82_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_datafim"]) || $this->pc82_datafim != "")
           $resac = db_query("insert into db_acount values($acount,2724,15522,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_datafim'))."','$this->pc82_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_obs"]) || $this->pc82_obs != "")
           $resac = db_query("insert into db_acount values($acount,2724,15523,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_obs'))."','$this->pc82_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_data"]) || $this->pc82_data != "")
           $resac = db_query("insert into db_acount values($acount,2724,15524,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_data'))."','$this->pc82_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_liberasol"]) || $this->pc82_liberasol != "")
           $resac = db_query("insert into db_acount values($acount,2724,15525,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_liberasol'))."','$this->pc82_liberasol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_liberaproc"]) || $this->pc82_liberaproc != "")
           $resac = db_query("insert into db_acount values($acount,2724,15526,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_liberaproc'))."','$this->pc82_liberaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_liberaaut"]) || $this->pc82_liberaaut != "")
           $resac = db_query("insert into db_acount values($acount,2724,15527,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_liberaaut'))."','$this->pc82_liberaaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc82_ativo"]) || $this->pc82_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2724,15528,'".AddSlashes(pg_result($resaco,$conresaco,'pc82_ativo'))."','$this->pc82_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Libera Fornecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc82_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Libera Fornecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc82_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc82_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15518,'$pc82_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2724,15518,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15519,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15520,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15521,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15522,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15523,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15524,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15525,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_liberasol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15526,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_liberaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15527,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_liberaaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2724,15528,'','".AddSlashes(pg_result($resaco,$iresaco,'pc82_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liberafornecedor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc82_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc82_sequencial = $pc82_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Libera Fornecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc82_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Libera Fornecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc82_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc82_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liberafornecedor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc82_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liberafornecedor ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = liberafornecedor.pc82_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liberafornecedor.pc82_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc82_sequencial!=null ){
         $sql2 .= " where liberafornecedor.pc82_sequencial = $pc82_sequencial "; 
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
   function sql_query_file ( $pc82_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liberafornecedor ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc82_sequencial!=null ){
         $sql2 .= " where liberafornecedor.pc82_sequencial = $pc82_sequencial "; 
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