<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: social
//CLASSE DA ENTIDADE cidadaobeneficio
class cl_cidadaobeneficio { 
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
   var $as08_sequencial = 0; 
   var $as08_programasocial = 0; 
   var $as08_mes = 0; 
   var $as08_ano = 0; 
   var $as08_situacao = null; 
   var $as08_nis = null; 
   var $as08_tipobeneficio = null; 
   var $as08_datasituacao_dia = null; 
   var $as08_datasituacao_mes = null; 
   var $as08_datasituacao_ano = null; 
   var $as08_datasituacao = null; 
   var $as08_dataconcessao_dia = null; 
   var $as08_dataconcessao_mes = null; 
   var $as08_dataconcessao_ano = null; 
   var $as08_dataconcessao = null; 
   var $as08_motivo = null; 
   var $as08_justificativa = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as08_sequencial = int4 = Código Cidadão Benefício 
                 as08_programasocial = int4 = Código do Programa Social 
                 as08_mes = int4 = Mês da Competência 
                 as08_ano = int4 = Ano da Competência 
                 as08_situacao = varchar(50) = Situação do Benefício 
                 as08_nis = varchar(20) = NIS do Beneficiário 
                 as08_tipobeneficio = varchar(50) = Tipo do Benefício 
                 as08_datasituacao = date = Data da Situação 
                 as08_dataconcessao = date = Data da Concessão 
                 as08_motivo = text = Motivo 
                 as08_justificativa = text = Justificativa 
                 ";
   //funcao construtor da classe 
   function cl_cidadaobeneficio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaobeneficio"); 
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
       $this->as08_sequencial = ($this->as08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_sequencial"]:$this->as08_sequencial);
       $this->as08_programasocial = ($this->as08_programasocial == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_programasocial"]:$this->as08_programasocial);
       $this->as08_mes = ($this->as08_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_mes"]:$this->as08_mes);
       $this->as08_ano = ($this->as08_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_ano"]:$this->as08_ano);
       $this->as08_situacao = ($this->as08_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_situacao"]:$this->as08_situacao);
       $this->as08_nis = ($this->as08_nis == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_nis"]:$this->as08_nis);
       $this->as08_tipobeneficio = ($this->as08_tipobeneficio == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_tipobeneficio"]:$this->as08_tipobeneficio);
       if($this->as08_datasituacao == ""){
         $this->as08_datasituacao_dia = ($this->as08_datasituacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_datasituacao_dia"]:$this->as08_datasituacao_dia);
         $this->as08_datasituacao_mes = ($this->as08_datasituacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_datasituacao_mes"]:$this->as08_datasituacao_mes);
         $this->as08_datasituacao_ano = ($this->as08_datasituacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_datasituacao_ano"]:$this->as08_datasituacao_ano);
         if($this->as08_datasituacao_dia != ""){
            $this->as08_datasituacao = $this->as08_datasituacao_ano."-".$this->as08_datasituacao_mes."-".$this->as08_datasituacao_dia;
         }
       }
       if($this->as08_dataconcessao == ""){
         $this->as08_dataconcessao_dia = ($this->as08_dataconcessao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao_dia"]:$this->as08_dataconcessao_dia);
         $this->as08_dataconcessao_mes = ($this->as08_dataconcessao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao_mes"]:$this->as08_dataconcessao_mes);
         $this->as08_dataconcessao_ano = ($this->as08_dataconcessao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao_ano"]:$this->as08_dataconcessao_ano);
         if($this->as08_dataconcessao_dia != ""){
            $this->as08_dataconcessao = $this->as08_dataconcessao_ano."-".$this->as08_dataconcessao_mes."-".$this->as08_dataconcessao_dia;
         }
       }
       $this->as08_motivo = ($this->as08_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_motivo"]:$this->as08_motivo);
       $this->as08_justificativa = ($this->as08_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_justificativa"]:$this->as08_justificativa);
     }else{
       $this->as08_sequencial = ($this->as08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as08_sequencial"]:$this->as08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as08_sequencial){ 
      $this->atualizacampos();
     if($this->as08_programasocial == null ){ 
       $this->erro_sql = " Campo Código do Programa Social nao Informado.";
       $this->erro_campo = "as08_programasocial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as08_mes == null ){ 
       $this->erro_sql = " Campo Mês da Competência nao Informado.";
       $this->erro_campo = "as08_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as08_ano == null ){ 
       $this->erro_sql = " Campo Ano da Competência nao Informado.";
       $this->erro_campo = "as08_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as08_situacao == null ){ 
       $this->erro_sql = " Campo Situação do Benefício nao Informado.";
       $this->erro_campo = "as08_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as08_nis == null ){ 
       $this->erro_sql = " Campo NIS do Beneficiário nao Informado.";
       $this->erro_campo = "as08_nis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as08_tipobeneficio == null ){ 
       $this->erro_sql = " Campo Tipo do Benefício nao Informado.";
       $this->erro_campo = "as08_tipobeneficio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as08_datasituacao == null ){ 
       $this->as08_datasituacao = "null";
     }
     if($this->as08_dataconcessao == null ){ 
       $this->as08_dataconcessao = "null";
     }
     if($this->as08_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "as08_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as08_sequencial == "" || $as08_sequencial == null ){
       $result = db_query("select nextval('cidadaobeneficio_as08_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaobeneficio_as08_sequencial_seq do campo: as08_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as08_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaobeneficio_as08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as08_sequencial)){
         $this->erro_sql = " Campo as08_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as08_sequencial = $as08_sequencial; 
       }
     }
     if(($this->as08_sequencial == null) || ($this->as08_sequencial == "") ){ 
       $this->erro_sql = " Campo as08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaobeneficio(
                                       as08_sequencial 
                                      ,as08_programasocial 
                                      ,as08_mes 
                                      ,as08_ano 
                                      ,as08_situacao 
                                      ,as08_nis 
                                      ,as08_tipobeneficio 
                                      ,as08_datasituacao 
                                      ,as08_dataconcessao 
                                      ,as08_motivo 
                                      ,as08_justificativa 
                       )
                values (
                                $this->as08_sequencial 
                               ,$this->as08_programasocial 
                               ,$this->as08_mes 
                               ,$this->as08_ano 
                               ,'$this->as08_situacao' 
                               ,'$this->as08_nis' 
                               ,'$this->as08_tipobeneficio' 
                               ,".($this->as08_datasituacao == "null" || $this->as08_datasituacao == ""?"null":"'".$this->as08_datasituacao."'")." 
                               ,".($this->as08_dataconcessao == "null" || $this->as08_dataconcessao == ""?"null":"'".$this->as08_dataconcessao."'")." 
                               ,'$this->as08_motivo' 
                               ,'$this->as08_justificativa' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaobeneficio ($this->as08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaobeneficio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaobeneficio ($this->as08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as08_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->as08_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19134,'$this->as08_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3401,19134,'','".AddSlashes(pg_result($resaco,0,'as08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19135,'','".AddSlashes(pg_result($resaco,0,'as08_programasocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19136,'','".AddSlashes(pg_result($resaco,0,'as08_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19137,'','".AddSlashes(pg_result($resaco,0,'as08_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19138,'','".AddSlashes(pg_result($resaco,0,'as08_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19139,'','".AddSlashes(pg_result($resaco,0,'as08_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19140,'','".AddSlashes(pg_result($resaco,0,'as08_tipobeneficio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19141,'','".AddSlashes(pg_result($resaco,0,'as08_datasituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19142,'','".AddSlashes(pg_result($resaco,0,'as08_dataconcessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19143,'','".AddSlashes(pg_result($resaco,0,'as08_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3401,19144,'','".AddSlashes(pg_result($resaco,0,'as08_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as08_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaobeneficio set ";
     $virgula = "";
     if(trim($this->as08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_sequencial"])){ 
       $sql  .= $virgula." as08_sequencial = $this->as08_sequencial ";
       $virgula = ",";
       if(trim($this->as08_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Cidadão Benefício nao Informado.";
         $this->erro_campo = "as08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_programasocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_programasocial"])){ 
       $sql  .= $virgula." as08_programasocial = $this->as08_programasocial ";
       $virgula = ",";
       if(trim($this->as08_programasocial) == null ){ 
         $this->erro_sql = " Campo Código do Programa Social nao Informado.";
         $this->erro_campo = "as08_programasocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_mes"])){ 
       $sql  .= $virgula." as08_mes = $this->as08_mes ";
       $virgula = ",";
       if(trim($this->as08_mes) == null ){ 
         $this->erro_sql = " Campo Mês da Competência nao Informado.";
         $this->erro_campo = "as08_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_ano"])){ 
       $sql  .= $virgula." as08_ano = $this->as08_ano ";
       $virgula = ",";
       if(trim($this->as08_ano) == null ){ 
         $this->erro_sql = " Campo Ano da Competência nao Informado.";
         $this->erro_campo = "as08_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_situacao"])){ 
       $sql  .= $virgula." as08_situacao = '$this->as08_situacao' ";
       $virgula = ",";
       if(trim($this->as08_situacao) == null ){ 
         $this->erro_sql = " Campo Situação do Benefício nao Informado.";
         $this->erro_campo = "as08_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_nis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_nis"])){ 
       $sql  .= $virgula." as08_nis = '$this->as08_nis' ";
       $virgula = ",";
       if(trim($this->as08_nis) == null ){ 
         $this->erro_sql = " Campo NIS do Beneficiário nao Informado.";
         $this->erro_campo = "as08_nis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_tipobeneficio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_tipobeneficio"])){ 
       $sql  .= $virgula." as08_tipobeneficio = '$this->as08_tipobeneficio' ";
       $virgula = ",";
       if(trim($this->as08_tipobeneficio) == null ){ 
         $this->erro_sql = " Campo Tipo do Benefício nao Informado.";
         $this->erro_campo = "as08_tipobeneficio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_datasituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_datasituacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as08_datasituacao_dia"] !="") ){ 
       $sql  .= $virgula." as08_datasituacao = '$this->as08_datasituacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as08_datasituacao_dia"])){ 
         $sql  .= $virgula." as08_datasituacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->as08_dataconcessao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao_dia"] !="") ){ 
       $sql  .= $virgula." as08_dataconcessao = '$this->as08_dataconcessao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao_dia"])){ 
         $sql  .= $virgula." as08_dataconcessao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->as08_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_motivo"])){ 
       $sql  .= $virgula." as08_motivo = '$this->as08_motivo' ";
       $virgula = ",";
       if(trim($this->as08_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "as08_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as08_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as08_justificativa"])){ 
       $sql  .= $virgula." as08_justificativa = '$this->as08_justificativa' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($as08_sequencial!=null){
       $sql .= " as08_sequencial = $this->as08_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {

       $resaco = $this->sql_record($this->sql_query_file($this->as08_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19134,'$this->as08_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_sequencial"]) || $this->as08_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3401,19134,'".AddSlashes(pg_result($resaco,$conresaco,'as08_sequencial'))."','$this->as08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_programasocial"]) || $this->as08_programasocial != "")
             $resac = db_query("insert into db_acount values($acount,3401,19135,'".AddSlashes(pg_result($resaco,$conresaco,'as08_programasocial'))."','$this->as08_programasocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_mes"]) || $this->as08_mes != "")
             $resac = db_query("insert into db_acount values($acount,3401,19136,'".AddSlashes(pg_result($resaco,$conresaco,'as08_mes'))."','$this->as08_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_ano"]) || $this->as08_ano != "")
             $resac = db_query("insert into db_acount values($acount,3401,19137,'".AddSlashes(pg_result($resaco,$conresaco,'as08_ano'))."','$this->as08_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_situacao"]) || $this->as08_situacao != "")
             $resac = db_query("insert into db_acount values($acount,3401,19138,'".AddSlashes(pg_result($resaco,$conresaco,'as08_situacao'))."','$this->as08_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_nis"]) || $this->as08_nis != "")
             $resac = db_query("insert into db_acount values($acount,3401,19139,'".AddSlashes(pg_result($resaco,$conresaco,'as08_nis'))."','$this->as08_nis',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_tipobeneficio"]) || $this->as08_tipobeneficio != "")
             $resac = db_query("insert into db_acount values($acount,3401,19140,'".AddSlashes(pg_result($resaco,$conresaco,'as08_tipobeneficio'))."','$this->as08_tipobeneficio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_datasituacao"]) || $this->as08_datasituacao != "")
             $resac = db_query("insert into db_acount values($acount,3401,19141,'".AddSlashes(pg_result($resaco,$conresaco,'as08_datasituacao'))."','$this->as08_datasituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_dataconcessao"]) || $this->as08_dataconcessao != "")
             $resac = db_query("insert into db_acount values($acount,3401,19142,'".AddSlashes(pg_result($resaco,$conresaco,'as08_dataconcessao'))."','$this->as08_dataconcessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_motivo"]) || $this->as08_motivo != "")
             $resac = db_query("insert into db_acount values($acount,3401,19143,'".AddSlashes(pg_result($resaco,$conresaco,'as08_motivo'))."','$this->as08_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as08_justificativa"]) || $this->as08_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,3401,19144,'".AddSlashes(pg_result($resaco,$conresaco,'as08_justificativa'))."','$this->as08_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaobeneficio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaobeneficio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as08_sequencial=null,$dbwhere=null) { 

     if (!isset($_SESSION["DB_usaAccount"])) {

       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($as08_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19134,'$as08_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3401,19134,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19135,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_programasocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19136,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19137,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19138,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19139,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_nis'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19140,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_tipobeneficio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19141,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_datasituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19142,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_dataconcessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19143,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3401,19144,'','".AddSlashes(pg_result($resaco,$iresaco,'as08_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaobeneficio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as08_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as08_sequencial = $as08_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaobeneficio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaobeneficio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as08_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaobeneficio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaobeneficio ";
     $sql2 = "";
     if($dbwhere==""){
       if($as08_sequencial!=null ){
         $sql2 .= " where cidadaobeneficio.as08_sequencial = $as08_sequencial "; 
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
   function sql_query_file ( $as08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaobeneficio ";
     $sql2 = "";
     if($dbwhere==""){
       if($as08_sequencial!=null ){
         $sql2 .= " where cidadaobeneficio.as08_sequencial = $as08_sequencial "; 
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

  
  function sql_query_beneficio_familia ( $as08_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
  	
  	$sql = "select ";
  	if ($campos != "*" ) {
  		$campos_sql = split("#",$campos);
  		$virgula = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {
  			
  			$sql    .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	} else {
  		$sql .= $campos;
  	}
  	$sql .= " from cidadaobeneficio ";
  	$sql .= " inner join cidadaocadastrounico      on as02_nis          = as08_nis ";
  	$sql .= " inner join cidadao                   on as02_cidadao      = ov02_sequencial ";
  	$sql .= " inner join cidadaocomposicaofamiliar on as03_cidadao      = as02_cidadao ";
  	$sql .= "                                     and as03_cidadao_seq  = as02_cidadao_seq ";
  	$sql .= " inner join cidadaofamilia            on as04_sequencial   = as03_cidadaofamilia  ";
  	$sql2 = "";
  	if ($dbwhere == "") {
  		
  		if ($as08_sequencial != null) {
  			$sql2 .= " where cidadaobeneficio.as08_sequencial = $as08_sequencial ";
  		}
    } else if ($dbwhere != "") {
  		$sql2 = " where $dbwhere";
    }
  	$sql .= $sql2;
  	if ($ordem != null) {
  		
  		$sql       .= " order by ";
  		$campos_sql = split("#",$ordem);
  		$virgula    = "";
  		for ($i = 0; $i < sizeof($campos_sql); $i++) {
  			
  		  $sql .= $virgula.$campos_sql[$i];
  		  $virgula = ",";
  		}
  	}
  	return $sql;
  }
}
?>