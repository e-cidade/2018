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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE processoouvidoriaprorrogacao
class cl_processoouvidoriaprorrogacao { 
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
   var $ov15_sequencial = 0; 
   var $ov15_protprocesso = 0; 
   var $ov15_coddepto = 0; 
   var $ov15_dtini_dia = null; 
   var $ov15_dtini_mes = null; 
   var $ov15_dtini_ano = null; 
   var $ov15_dtini = null; 
   var $ov15_dtfim_dia = null; 
   var $ov15_dtfim_mes = null; 
   var $ov15_dtfim_ano = null; 
   var $ov15_dtfim = null; 
   var $ov15_motivo = null; 
   var $ov15_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ov15_sequencial = int4 = Sequencial 
                 ov15_protprocesso = int4 = Processo 
                 ov15_coddepto = int4 = Departamento 
                 ov15_dtini = date = Data Inicial 
                 ov15_dtfim = date = Data Final 
                 ov15_motivo = text = Motivo 
                 ov15_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_processoouvidoriaprorrogacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoouvidoriaprorrogacao"); 
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
       $this->ov15_sequencial = ($this->ov15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_sequencial"]:$this->ov15_sequencial);
       $this->ov15_protprocesso = ($this->ov15_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_protprocesso"]:$this->ov15_protprocesso);
       $this->ov15_coddepto = ($this->ov15_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_coddepto"]:$this->ov15_coddepto);
       if($this->ov15_dtini == ""){
         $this->ov15_dtini_dia = ($this->ov15_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_dtini_dia"]:$this->ov15_dtini_dia);
         $this->ov15_dtini_mes = ($this->ov15_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_dtini_mes"]:$this->ov15_dtini_mes);
         $this->ov15_dtini_ano = ($this->ov15_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_dtini_ano"]:$this->ov15_dtini_ano);
         if($this->ov15_dtini_dia != ""){
            $this->ov15_dtini = $this->ov15_dtini_ano."-".$this->ov15_dtini_mes."-".$this->ov15_dtini_dia;
         }
       }
       if($this->ov15_dtfim == ""){
         $this->ov15_dtfim_dia = ($this->ov15_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_dtfim_dia"]:$this->ov15_dtfim_dia);
         $this->ov15_dtfim_mes = ($this->ov15_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_dtfim_mes"]:$this->ov15_dtfim_mes);
         $this->ov15_dtfim_ano = ($this->ov15_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_dtfim_ano"]:$this->ov15_dtfim_ano);
         if($this->ov15_dtfim_dia != ""){
            $this->ov15_dtfim = $this->ov15_dtfim_ano."-".$this->ov15_dtfim_mes."-".$this->ov15_dtfim_dia;
         }
       }
       $this->ov15_motivo = ($this->ov15_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_motivo"]:$this->ov15_motivo);
       $this->ov15_ativo = ($this->ov15_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ov15_ativo"]:$this->ov15_ativo);
     }else{
       $this->ov15_sequencial = ($this->ov15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ov15_sequencial"]:$this->ov15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ov15_sequencial){ 
      $this->atualizacampos();
     if($this->ov15_protprocesso == null ){ 
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "ov15_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov15_coddepto == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "ov15_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov15_dtini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ov15_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov15_dtfim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ov15_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ov15_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ov15_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ov15_sequencial == "" || $ov15_sequencial == null ){
       $result = db_query("select nextval('processoouvidoriaprorrogacao_ov15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoouvidoriaprorrogacao_ov15_sequencial_seq do campo: ov15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ov15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processoouvidoriaprorrogacao_ov15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ov15_sequencial)){
         $this->erro_sql = " Campo ov15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ov15_sequencial = $ov15_sequencial; 
       }
     }
     if(($this->ov15_sequencial == null) || ($this->ov15_sequencial == "") ){ 
       $this->erro_sql = " Campo ov15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoouvidoriaprorrogacao(
                                       ov15_sequencial 
                                      ,ov15_protprocesso 
                                      ,ov15_coddepto 
                                      ,ov15_dtini 
                                      ,ov15_dtfim 
                                      ,ov15_motivo 
                                      ,ov15_ativo 
                       )
                values (
                                $this->ov15_sequencial 
                               ,$this->ov15_protprocesso 
                               ,$this->ov15_coddepto 
                               ,".($this->ov15_dtini == "null" || $this->ov15_dtini == ""?"null":"'".$this->ov15_dtini."'")." 
                               ,".($this->ov15_dtfim == "null" || $this->ov15_dtfim == ""?"null":"'".$this->ov15_dtfim."'")." 
                               ,'$this->ov15_motivo' 
                               ,'$this->ov15_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prazo por setor do processo de ouvidoria ($this->ov15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prazo por setor do processo de ouvidoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prazo por setor do processo de ouvidoria ($this->ov15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ov15_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14812,'$this->ov15_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2607,14812,'','".AddSlashes(pg_result($resaco,0,'ov15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2607,14813,'','".AddSlashes(pg_result($resaco,0,'ov15_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2607,14817,'','".AddSlashes(pg_result($resaco,0,'ov15_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2607,14814,'','".AddSlashes(pg_result($resaco,0,'ov15_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2607,14815,'','".AddSlashes(pg_result($resaco,0,'ov15_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2607,14816,'','".AddSlashes(pg_result($resaco,0,'ov15_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2607,14818,'','".AddSlashes(pg_result($resaco,0,'ov15_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ov15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update processoouvidoriaprorrogacao set ";
     $virgula = "";
     if(trim($this->ov15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_sequencial"])){ 
       $sql  .= $virgula." ov15_sequencial = $this->ov15_sequencial ";
       $virgula = ",";
       if(trim($this->ov15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ov15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov15_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_protprocesso"])){ 
       $sql  .= $virgula." ov15_protprocesso = $this->ov15_protprocesso ";
       $virgula = ",";
       if(trim($this->ov15_protprocesso) == null ){ 
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "ov15_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov15_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_coddepto"])){ 
       $sql  .= $virgula." ov15_coddepto = $this->ov15_coddepto ";
       $virgula = ",";
       if(trim($this->ov15_coddepto) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "ov15_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ov15_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ov15_dtini_dia"] !="") ){ 
       $sql  .= $virgula." ov15_dtini = '$this->ov15_dtini' ";
       $virgula = ",";
       if(trim($this->ov15_dtini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ov15_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_dtini_dia"])){ 
         $sql  .= $virgula." ov15_dtini = null ";
         $virgula = ",";
         if(trim($this->ov15_dtini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ov15_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ov15_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ov15_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." ov15_dtfim = '$this->ov15_dtfim' ";
       $virgula = ",";
       if(trim($this->ov15_dtfim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ov15_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_dtfim_dia"])){ 
         $sql  .= $virgula." ov15_dtfim = null ";
         $virgula = ",";
         if(trim($this->ov15_dtfim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ov15_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ov15_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_motivo"])){ 
       $sql  .= $virgula." ov15_motivo = '$this->ov15_motivo' ";
       $virgula = ",";
     }
     if(trim($this->ov15_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ov15_ativo"])){ 
       $sql  .= $virgula." ov15_ativo = '$this->ov15_ativo' ";
       $virgula = ",";
       if(trim($this->ov15_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ov15_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ov15_sequencial!=null){
       $sql .= " ov15_sequencial = $this->ov15_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ov15_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14812,'$this->ov15_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_sequencial"]) || $this->ov15_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2607,14812,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_sequencial'))."','$this->ov15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_protprocesso"]) || $this->ov15_protprocesso != "")
           $resac = db_query("insert into db_acount values($acount,2607,14813,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_protprocesso'))."','$this->ov15_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_coddepto"]) || $this->ov15_coddepto != "")
           $resac = db_query("insert into db_acount values($acount,2607,14817,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_coddepto'))."','$this->ov15_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_dtini"]) || $this->ov15_dtini != "")
           $resac = db_query("insert into db_acount values($acount,2607,14814,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_dtini'))."','$this->ov15_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_dtfim"]) || $this->ov15_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,2607,14815,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_dtfim'))."','$this->ov15_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_motivo"]) || $this->ov15_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2607,14816,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_motivo'))."','$this->ov15_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ov15_ativo"]) || $this->ov15_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2607,14818,'".AddSlashes(pg_result($resaco,$conresaco,'ov15_ativo'))."','$this->ov15_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prazo por setor do processo de ouvidoria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prazo por setor do processo de ouvidoria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ov15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ov15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ov15_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ov15_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14812,'$ov15_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2607,14812,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2607,14813,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2607,14817,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2607,14814,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2607,14815,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2607,14816,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2607,14818,'','".AddSlashes(pg_result($resaco,$iresaco,'ov15_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoouvidoriaprorrogacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ov15_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ov15_sequencial = $ov15_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prazo por setor do processo de ouvidoria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ov15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prazo por setor do processo de ouvidoria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ov15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ov15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoouvidoriaprorrogacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ov15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoouvidoriaprorrogacao ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = processoouvidoriaprorrogacao.ov15_coddepto";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = processoouvidoriaprorrogacao.ov15_protprocesso";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  as a on   a.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ov15_sequencial!=null ){
         $sql2 .= " where processoouvidoriaprorrogacao.ov15_sequencial = $ov15_sequencial "; 
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
   function sql_query_file ( $ov15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoouvidoriaprorrogacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ov15_sequencial!=null ){
         $sql2 .= " where processoouvidoriaprorrogacao.ov15_sequencial = $ov15_sequencial "; 
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
	function sql_query_ouvidoria ( $ov15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoouvidoriaprorrogacao ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = processoouvidoriaprorrogacao.ov15_coddepto";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = processoouvidoriaprorrogacao.ov15_protprocesso";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  as a on   a.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart as depart on depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ov15_sequencial!=null ){
         $sql2 .= " where processoouvidoriaprorrogacao.ov15_sequencial = $ov15_sequencial "; 
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