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

//MODULO: caixa
//CLASSE DA ENTIDADE arrevenc
class cl_arrevenc { 
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
   var $k00_sequencial = 0; 
   var $k00_arrevenclog = 0; 
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_dtini_dia = null; 
   var $k00_dtini_mes = null; 
   var $k00_dtini_ano = null; 
   var $k00_dtini = null; 
   var $k00_dtfim_dia = null; 
   var $k00_dtfim_mes = null; 
   var $k00_dtfim_ano = null; 
   var $k00_dtfim = null; 
   var $k00_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_sequencial = int4 = Codigo 
                 k00_arrevenclog = int4 = Codigo arrevenclog 
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_dtini = date = Inicio Processo 
                 k00_dtfim = date = Fim Processo 
                 k00_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_arrevenc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrevenc"); 
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
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
       $this->k00_arrevenclog = ($this->k00_arrevenclog == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_arrevenclog"]:$this->k00_arrevenclog);
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       if($this->k00_dtini == ""){
         $this->k00_dtini_dia = ($this->k00_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtini_dia"]:$this->k00_dtini_dia);
         $this->k00_dtini_mes = ($this->k00_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtini_mes"]:$this->k00_dtini_mes);
         $this->k00_dtini_ano = ($this->k00_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtini_ano"]:$this->k00_dtini_ano);
         if($this->k00_dtini_dia != ""){
            $this->k00_dtini = $this->k00_dtini_ano."-".$this->k00_dtini_mes."-".$this->k00_dtini_dia;
         }
       }
       if($this->k00_dtfim == ""){
         $this->k00_dtfim_dia = ($this->k00_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtfim_dia"]:$this->k00_dtfim_dia);
         $this->k00_dtfim_mes = ($this->k00_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtfim_mes"]:$this->k00_dtfim_mes);
         $this->k00_dtfim_ano = ($this->k00_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtfim_ano"]:$this->k00_dtfim_ano);
         if($this->k00_dtfim_dia != ""){
            $this->k00_dtfim = $this->k00_dtfim_ano."-".$this->k00_dtfim_mes."-".$this->k00_dtfim_dia;
         }
       }
       $this->k00_obs = ($this->k00_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_obs"]:$this->k00_obs);
     }else{
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k00_sequencial){ 
      $this->atualizacampos();
     if($this->k00_arrevenclog == null ){ 
       $this->erro_sql = " Campo Codigo arrevenclog nao Informado.";
       $this->erro_campo = "k00_arrevenclog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k00_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k00_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtini == null ){ 
       $this->erro_sql = " Campo Inicio Processo nao Informado.";
       $this->erro_campo = "k00_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtfim == null ){ 
       $this->k00_dtfim = "null";
     }
     if($this->k00_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "k00_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k00_sequencial == "" || $k00_sequencial == null ){
       $result = db_query("select nextval('arrevenc_k00_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arrevenc_k00_sequencial_seq do campo: k00_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k00_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arrevenc_k00_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_sequencial)){
         $this->erro_sql = " Campo k00_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_sequencial = $k00_sequencial; 
       }
     }
     if(($this->k00_sequencial == null) || ($this->k00_sequencial == "") ){ 
       $this->erro_sql = " Campo k00_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrevenc(
                                       k00_sequencial 
                                      ,k00_arrevenclog 
                                      ,k00_numpre 
                                      ,k00_numpar 
                                      ,k00_dtini 
                                      ,k00_dtfim 
                                      ,k00_obs 
                       )
                values (
                                $this->k00_sequencial 
                               ,$this->k00_arrevenclog 
                               ,$this->k00_numpre 
                               ,$this->k00_numpar 
                               ,".($this->k00_dtini == "null" || $this->k00_dtini == ""?"null":"'".$this->k00_dtini."'")." 
                               ,".($this->k00_dtfim == "null" || $this->k00_dtfim == ""?"null":"'".$this->k00_dtfim."'")." 
                               ,'$this->k00_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prorrogação de Vencimentos ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prorrogação de Vencimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prorrogação de Vencimentos ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,638,11816,'','".AddSlashes(pg_result($resaco,0,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,638,11815,'','".AddSlashes(pg_result($resaco,0,'k00_arrevenclog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,638,361,'','".AddSlashes(pg_result($resaco,0,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,638,362,'','".AddSlashes(pg_result($resaco,0,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,638,4766,'','".AddSlashes(pg_result($resaco,0,'k00_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,638,4767,'','".AddSlashes(pg_result($resaco,0,'k00_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,638,4772,'','".AddSlashes(pg_result($resaco,0,'k00_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k00_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update arrevenc set ";
     $virgula = "";
     if(trim($this->k00_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"])){ 
       $sql  .= $virgula." k00_sequencial = $this->k00_sequencial ";
       $virgula = ",";
       if(trim($this->k00_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "k00_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_arrevenclog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_arrevenclog"])){ 
       $sql  .= $virgula." k00_arrevenclog = $this->k00_arrevenclog ";
       $virgula = ",";
       if(trim($this->k00_arrevenclog) == null ){ 
         $this->erro_sql = " Campo Codigo arrevenclog nao Informado.";
         $this->erro_campo = "k00_arrevenclog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){ 
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){ 
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtini_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtini = '$this->k00_dtini' ";
       $virgula = ",";
       if(trim($this->k00_dtini) == null ){ 
         $this->erro_sql = " Campo Inicio Processo nao Informado.";
         $this->erro_campo = "k00_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtini_dia"])){ 
         $sql  .= $virgula." k00_dtini = null ";
         $virgula = ",";
         if(trim($this->k00_dtini) == null ){ 
           $this->erro_sql = " Campo Inicio Processo nao Informado.";
           $this->erro_campo = "k00_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtfim = '$this->k00_dtfim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtfim_dia"])){ 
         $sql  .= $virgula." k00_dtfim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k00_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_obs"])){ 
       $sql  .= $virgula." k00_obs = '$this->k00_obs' ";
       $virgula = ",";
       if(trim($this->k00_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "k00_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k00_sequencial!=null){
       $sql .= " k00_sequencial = $this->k00_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,638,11816,'".AddSlashes(pg_result($resaco,$conresaco,'k00_sequencial'))."','$this->k00_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_arrevenclog"]))
           $resac = db_query("insert into db_acount values($acount,638,11815,'".AddSlashes(pg_result($resaco,$conresaco,'k00_arrevenclog'))."','$this->k00_arrevenclog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"]))
           $resac = db_query("insert into db_acount values($acount,638,361,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpre'))."','$this->k00_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"]))
           $resac = db_query("insert into db_acount values($acount,638,362,'".AddSlashes(pg_result($resaco,$conresaco,'k00_numpar'))."','$this->k00_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtini"]))
           $resac = db_query("insert into db_acount values($acount,638,4766,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtini'))."','$this->k00_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,638,4767,'".AddSlashes(pg_result($resaco,$conresaco,'k00_dtfim'))."','$this->k00_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_obs"]))
           $resac = db_query("insert into db_acount values($acount,638,4772,'".AddSlashes(pg_result($resaco,$conresaco,'k00_obs'))."','$this->k00_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prorrogação de Vencimentos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prorrogação de Vencimentos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k00_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$k00_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,638,11816,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,638,11815,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_arrevenclog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,638,361,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,638,362,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,638,4766,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,638,4767,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,638,4772,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arrevenc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_sequencial = $k00_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prorrogação de Vencimentos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prorrogação de Vencimentos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arrevenc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrevenc ";
     $sql .= "      inner join arrevenclog  on  arrevenclog.k75_sequencial = arrevenc.k00_arrevenclog";
     $sql .= "      inner join db_config  on  db_config.codigo = arrevenclog.k75_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = arrevenclog.k75_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where arrevenc.k00_sequencial = $k00_sequencial "; 
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
   function sql_query_file ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrevenc ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where arrevenc.k00_sequencial = $k00_sequencial "; 
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
   function sql_query_instit ( $oid = null,$campos="*",$ordem=null,$dbwhere="",$instit=""){ 
     
     if ($instit == "") {
       $inner = " inner join arreinstit on arreinstit.k00_numpre = arrevenc.k00_numpre and arreinstit.k00_instit = ".db_getsession('DB_instit') ;
     }else{
       $inner = " inner join arreinstit on arreinstit.k00_numpre = arrevenc.k00_numpre and arreinstit.k00_instit = {$instit}";
     }

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
     $sql .= " from arrevenc {$inner}";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where arrevenc.oid = $oid";
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