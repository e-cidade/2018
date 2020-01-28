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

//MODULO: atendimento
//CLASSE DA ENTIDADE atendimento
class cl_atendimento { 
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
   var $at02_codatend = 0; 
   var $at02_codcli = 0; 
   var $at02_codtipo = 0; 
   var $at02_solicitado = null; 
   var $at02_feito = null; 
   var $at02_dataini_dia = null; 
   var $at02_dataini_mes = null; 
   var $at02_dataini_ano = null; 
   var $at02_dataini = null; 
   var $at02_datafim_dia = null; 
   var $at02_datafim_mes = null; 
   var $at02_datafim_ano = null; 
   var $at02_datafim = null; 
   var $at02_horaini = null; 
   var $at02_horafim = null; 
   var $at02_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at02_codatend = int4 = Atendimento 
                 at02_codcli = int4 = Cliente 
                 at02_codtipo = int4 = Tipo de atendimento 
                 at02_solicitado = text = Solicitação 
                 at02_feito = text = Executado 
                 at02_dataini = date = Data Inicial 
                 at02_datafim = date = Data final 
                 at02_horaini = varchar(5) = Hora Inicial 
                 at02_horafim = varchar(5) = Hora final 
                 at02_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_atendimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendimento"); 
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
       $this->at02_codatend = ($this->at02_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_codatend"]:$this->at02_codatend);
       $this->at02_codcli = ($this->at02_codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_codcli"]:$this->at02_codcli);
       $this->at02_codtipo = ($this->at02_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_codtipo"]:$this->at02_codtipo);
       $this->at02_solicitado = ($this->at02_solicitado == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_solicitado"]:$this->at02_solicitado);
       $this->at02_feito = ($this->at02_feito == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_feito"]:$this->at02_feito);
       if($this->at02_dataini == ""){
         $this->at02_dataini_dia = ($this->at02_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_dataini_dia"]:$this->at02_dataini_dia);
         $this->at02_dataini_mes = ($this->at02_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_dataini_mes"]:$this->at02_dataini_mes);
         $this->at02_dataini_ano = ($this->at02_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_dataini_ano"]:$this->at02_dataini_ano);
         if($this->at02_dataini_dia != ""){
            $this->at02_dataini = $this->at02_dataini_ano."-".$this->at02_dataini_mes."-".$this->at02_dataini_dia;
         }
       }
       if($this->at02_datafim == ""){
         $this->at02_datafim_dia = ($this->at02_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_datafim_dia"]:$this->at02_datafim_dia);
         $this->at02_datafim_mes = ($this->at02_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_datafim_mes"]:$this->at02_datafim_mes);
         $this->at02_datafim_ano = ($this->at02_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_datafim_ano"]:$this->at02_datafim_ano);
         if($this->at02_datafim_dia != ""){
            $this->at02_datafim = $this->at02_datafim_ano."-".$this->at02_datafim_mes."-".$this->at02_datafim_dia;
         }
       }
       $this->at02_horaini = ($this->at02_horaini == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_horaini"]:$this->at02_horaini);
       $this->at02_horafim = ($this->at02_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_horafim"]:$this->at02_horafim);
       $this->at02_observacao = ($this->at02_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_observacao"]:$this->at02_observacao);
     }else{
       $this->at02_codatend = ($this->at02_codatend == ""?@$GLOBALS["HTTP_POST_VARS"]["at02_codatend"]:$this->at02_codatend);
     }
   }
   // funcao para inclusao
   function incluir ($at02_codatend){ 
      $this->atualizacampos();
     if($this->at02_codcli == null ){ 
       $this->erro_sql = " Campo Cliente nao Informado.";
       $this->erro_campo = "at02_codcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at02_codtipo == null ){ 
       $this->erro_sql = " Campo Tipo de atendimento nao Informado.";
       $this->erro_campo = "at02_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at02_dataini == null ){ 
       $this->at02_dataini = "null";
     }
     if($this->at02_datafim == null ){ 
       $this->at02_datafim = "null";
     }
     if($at02_codatend == "" || $at02_codatend == null ){
       $result = db_query("select nextval('atendimento_at02_codatend_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendimento_at02_codatend_seq do campo: at02_codatend"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at02_codatend = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atendimento_at02_codatend_seq");
       if(($result != false) && (pg_result($result,0,0) < $at02_codatend)){
         $this->erro_sql = " Campo at02_codatend maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at02_codatend = $at02_codatend; 
       }
     }
     if(($this->at02_codatend == null) || ($this->at02_codatend == "") ){ 
       $this->erro_sql = " Campo at02_codatend nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendimento(
                                       at02_codatend 
                                      ,at02_codcli 
                                      ,at02_codtipo 
                                      ,at02_solicitado 
                                      ,at02_feito 
                                      ,at02_dataini 
                                      ,at02_datafim 
                                      ,at02_horaini 
                                      ,at02_horafim 
                                      ,at02_observacao 
                       )
                values (
                                $this->at02_codatend 
                               ,$this->at02_codcli 
                               ,$this->at02_codtipo 
                               ,'$this->at02_solicitado' 
                               ,'$this->at02_feito' 
                               ,".($this->at02_dataini == "null" || $this->at02_dataini == ""?"null":"'".$this->at02_dataini."'")." 
                               ,".($this->at02_datafim == "null" || $this->at02_datafim == ""?"null":"'".$this->at02_datafim."'")." 
                               ,'$this->at02_horaini' 
                               ,'$this->at02_horafim' 
                               ,'$this->at02_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atendimento ($this->at02_codatend) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atendimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atendimento ($this->at02_codatend) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at02_codatend;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at02_codatend));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2545,'$this->at02_codatend','I')");
       $resac = db_query("insert into db_acount values($acount,417,2545,'','".AddSlashes(pg_result($resaco,0,'at02_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2546,'','".AddSlashes(pg_result($resaco,0,'at02_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2547,'','".AddSlashes(pg_result($resaco,0,'at02_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2548,'','".AddSlashes(pg_result($resaco,0,'at02_solicitado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2549,'','".AddSlashes(pg_result($resaco,0,'at02_feito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2550,'','".AddSlashes(pg_result($resaco,0,'at02_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2551,'','".AddSlashes(pg_result($resaco,0,'at02_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2552,'','".AddSlashes(pg_result($resaco,0,'at02_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2553,'','".AddSlashes(pg_result($resaco,0,'at02_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,417,2554,'','".AddSlashes(pg_result($resaco,0,'at02_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at02_codatend=null) { 
      $this->atualizacampos();
     $sql = " update atendimento set ";
     $virgula = "";
     if(trim($this->at02_codatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_codatend"])){ 
       $sql  .= $virgula." at02_codatend = $this->at02_codatend ";
       $virgula = ",";
       if(trim($this->at02_codatend) == null ){ 
         $this->erro_sql = " Campo Atendimento nao Informado.";
         $this->erro_campo = "at02_codatend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at02_codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_codcli"])){ 
       $sql  .= $virgula." at02_codcli = $this->at02_codcli ";
       $virgula = ",";
       if(trim($this->at02_codcli) == null ){ 
         $this->erro_sql = " Campo Cliente nao Informado.";
         $this->erro_campo = "at02_codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at02_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_codtipo"])){ 
       $sql  .= $virgula." at02_codtipo = $this->at02_codtipo ";
       $virgula = ",";
       if(trim($this->at02_codtipo) == null ){ 
         $this->erro_sql = " Campo Tipo de atendimento nao Informado.";
         $this->erro_campo = "at02_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at02_solicitado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_solicitado"])){ 
       $sql  .= $virgula." at02_solicitado = '$this->at02_solicitado' ";
       $virgula = ",";
     }
     if(trim($this->at02_feito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_feito"])){ 
       $sql  .= $virgula." at02_feito = '$this->at02_feito' ";
       $virgula = ",";
     }
     if(trim($this->at02_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at02_dataini_dia"] !="") ){ 
       $sql  .= $virgula." at02_dataini = '$this->at02_dataini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at02_dataini_dia"])){ 
         $sql  .= $virgula." at02_dataini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at02_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at02_datafim_dia"] !="") ){ 
       $sql  .= $virgula." at02_datafim = '$this->at02_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at02_datafim_dia"])){ 
         $sql  .= $virgula." at02_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at02_horaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_horaini"])){ 
       $sql  .= $virgula." at02_horaini = '$this->at02_horaini' ";
       $virgula = ",";
     }
     if(trim($this->at02_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_horafim"])){ 
       $sql  .= $virgula." at02_horafim = '$this->at02_horafim' ";
       $virgula = ",";
     }
     if(trim($this->at02_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at02_observacao"])){ 
       $sql  .= $virgula." at02_observacao = '$this->at02_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at02_codatend!=null){
       $sql .= " at02_codatend = $this->at02_codatend";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at02_codatend));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2545,'$this->at02_codatend','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_codatend"]))
           $resac = db_query("insert into db_acount values($acount,417,2545,'".AddSlashes(pg_result($resaco,$conresaco,'at02_codatend'))."','$this->at02_codatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_codcli"]))
           $resac = db_query("insert into db_acount values($acount,417,2546,'".AddSlashes(pg_result($resaco,$conresaco,'at02_codcli'))."','$this->at02_codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,417,2547,'".AddSlashes(pg_result($resaco,$conresaco,'at02_codtipo'))."','$this->at02_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_solicitado"]))
           $resac = db_query("insert into db_acount values($acount,417,2548,'".AddSlashes(pg_result($resaco,$conresaco,'at02_solicitado'))."','$this->at02_solicitado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_feito"]))
           $resac = db_query("insert into db_acount values($acount,417,2549,'".AddSlashes(pg_result($resaco,$conresaco,'at02_feito'))."','$this->at02_feito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_dataini"]))
           $resac = db_query("insert into db_acount values($acount,417,2550,'".AddSlashes(pg_result($resaco,$conresaco,'at02_dataini'))."','$this->at02_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_datafim"]))
           $resac = db_query("insert into db_acount values($acount,417,2551,'".AddSlashes(pg_result($resaco,$conresaco,'at02_datafim'))."','$this->at02_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_horaini"]))
           $resac = db_query("insert into db_acount values($acount,417,2552,'".AddSlashes(pg_result($resaco,$conresaco,'at02_horaini'))."','$this->at02_horaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_horafim"]))
           $resac = db_query("insert into db_acount values($acount,417,2553,'".AddSlashes(pg_result($resaco,$conresaco,'at02_horafim'))."','$this->at02_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at02_observacao"]))
           $resac = db_query("insert into db_acount values($acount,417,2554,'".AddSlashes(pg_result($resaco,$conresaco,'at02_observacao'))."','$this->at02_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atendimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at02_codatend;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atendimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at02_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at02_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at02_codatend=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at02_codatend));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2545,'$at02_codatend','E')");
         $resac = db_query("insert into db_acount values($acount,417,2545,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_codatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2546,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2547,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2548,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_solicitado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2549,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_feito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2550,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2551,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2552,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_horaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2553,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,417,2554,'','".AddSlashes(pg_result($resaco,$iresaco,'at02_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendimento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at02_codatend != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at02_codatend = $at02_codatend ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atendimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at02_codatend;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atendimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at02_codatend;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at02_codatend;
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
        $this->erro_sql   = "Record Vazio na Tabela:atendimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at02_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimento ";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = atendimento.at02_codcli";
     $sql .= "      inner join tipoatend  on  tipoatend.at04_codtipo = atendimento.at02_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($at02_codatend!=null ){
         $sql2 .= " where atendimento.at02_codatend = $at02_codatend "; 
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
  /* 
  function sql_query_alt ( $at02_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimento ";
     $sql .= " left join atendimentolanc on atendimentolanc.at06_codatend = atendimento.at02_codatend ";
     $sql .= "      left join atendimentoorigem as atendorig  on atendorig.at11_origematend   = atendimento.at02_codatend or
                                                                 atendorig.at11_novoatend     = atendimento.at02_codatend"; 
     $sql .= "      left join tecnico                         on tecnico.at03_codatend        = atendimento.at02_codatend";
     $sql .= "      left join db_usuarios                     on db_usuarios.id_usuario       = tecnico.at03_id_usuario";
     $sql .= "      left join clientes                        on  clientes.at01_codcli        = atendimento.at02_codcli"; 
     $sql .= "      left join atendimentousu                  on atendimentousu.at20_codatend = atendimento.at02_codatend";
     $sql .= "      left join db_usuclientes                  on db_usuclientes.at10_usuario  = atendimentousu.at20_usuario and
                                                                 db_usuclientes.at10_codcli   = atendimento.at02_codcli";
     $sql .= "      left join tipoatend                       on tipoatend.at04_codtipo       = atendimento.at02_codtipo";
     $sql .= "      left join atenditem                       on atenditem.at05_codatend      = atendimento.at02_codatend";
	 $sql .= "      left join atendimentosituacao			  on atendimentosituacao.at16_atendimento = atendimento.at02_codatend";
     $sql .= "      left join atendimentomod                  on atendimentomod.at08_atend    =atendimento.at02_codatend";
     $sql .= "      left join db_modulos                     on db_modulos.id_item           = atendimentomod.at08_modulo";
     $sql2 = "";
     if($dbwhere==""){
       if($at02_codatend!=null ){
         $sql2 .= " where atendimento.at02_codatend = $at02_codatend "; 
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
  }*/
  
   function sql_query_file ( $at02_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimento ";
     $sql2 = "";
     if($dbwhere==""){
       if($at02_codatend!=null ){
         $sql2 .= " where atendimento.at02_codatend = $at02_codatend "; 
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
   function sql_query_inc ( $at02_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimento ";
     $sql .= " left join atendimentolanc on atendimentolanc.at06_codatend = atendimento.at02_codatend ";
     $sql .= "      left join atendimentoorigem as atendorig  on atendorig.at11_origematend   = atendimento.at02_codatend or
                                                                 atendorig.at11_novoatend     = atendimento.at02_codatend"; 
     $sql .= "      left join tecnico                         on tecnico.at03_codatend        = atendimento.at02_codatend";
     $sql .= "      left join db_usuarios                     on db_usuarios.id_usuario       = tecnico.at03_id_usuario";
     $sql .= "      left join clientes                        on  clientes.at01_codcli        = atendimento.at02_codcli"; 
     $sql .= "      left join atendimentousu                  on atendimentousu.at20_codatend = atendimento.at02_codatend";
     $sql .= "      left join db_usuclientes                  on db_usuclientes.at10_usuario  = atendimentousu.at20_usuario and
                                                                 db_usuclientes.at10_codcli   = atendimento.at02_codcli";
     $sql .= "      left join tipoatend                       on tipoatend.at04_codtipo       = atendimento.at02_codtipo";
     $sql .= "      left join atenditem                       on atenditem.at05_codatend      = atendimento.at02_codatend";
     $sql .= "      left join atendimentosituacao							on atendimentosituacao.at16_atendimento = atendimento.at02_codatend";
     $sql .= "      left join atendarea                       on at28_atendimento             = at02_codatend";
     $sql .= "      left join atendcadarea                    on at26_sequencial              = at28_atendcadarea";
     $sql .= "      left join atendareatec on at27_atendcadarea=at26_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($at02_codatend!=null ){
         $sql2 .= " where atendimento.at02_codatend = $at02_codatend "; 
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
    // die($sql);
     return $sql;
  }
   function sql_query_sup ( $at02_codatend=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendimento ";
     $sql .= " left join atendimentolanc on atendimentolanc.at06_codatend = atendimento.at02_codatend ";
     $sql .= "      left join atendimentoorigem as atendorig  on atendorig.at11_origematend   = atendimento.at02_codatend or
                                                                 atendorig.at11_novoatend     = atendimento.at02_codatend"; 
     $sql .= "      left join tecnico                         on tecnico.at03_codatend        = atendimento.at02_codatend";
     $sql .= "      left join db_usuarios                     on db_usuarios.id_usuario       = tecnico.at03_id_usuario";
     $sql .= "      left join clientes                        on  clientes.at01_codcli        = atendimento.at02_codcli"; 
     $sql .= "      left join atendimentousu                  on atendimentousu.at20_codatend = atendimento.at02_codatend";
     $sql .= "      left join db_usuclientes                  on db_usuclientes.at10_usuario  = atendimentousu.at20_usuario and
                                                                 db_usuclientes.at10_codcli   = atendimento.at02_codcli";
     $sql .= "      left join tipoatend                       on tipoatend.at04_codtipo       = atendimento.at02_codtipo";
     $sql .= "      left join atenditem                       on atenditem.at05_codatend      = atendimento.at02_codatend";
	 $sql .= "      left join atendimentosituacao			  on atendimentosituacao.at16_atendimento = atendimento.at02_codatend";
     $sql .= "      left join atenditemmod                    on at22_atenditem               = at05_seq";
	 $sql .= "      left join atenditemsyscadproced           on at29_atenditem               = at05_seq";  
	 $sql .= "      left join db_syscadproced                 on at29_syscadproced            = codproced ";
	 $sql .= "      left join db_modulos                      on db_syscadproced.codmod       = db_modulos.id_item";
	 $sql .= "      left join atenditemmotivo                 on at34_atenditem               = at05_seq";
	 
     $sql2 = "";
     if($dbwhere==""){
       if($at02_codatend!=null ){
         $sql2 .= " where atendimento.at02_codatend = $at02_codatend "; 
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
   function sql_query_tecnico ( $at02_codatend=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from atendimento ";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = atendimento.at02_codcli";
     $sql .= "      inner join tecnico  on  tecnico.at03_codatend = atendimento.at02_codatend";
     $sql2 = "";
     if($dbwhere==""){
       if($at02_codatend!=null ){
         $sql2 .= " where atendimento.at02_codatend = $at02_codatend ";
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