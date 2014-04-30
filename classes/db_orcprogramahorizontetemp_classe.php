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
//CLASSE DA ENTIDADE orcprogramahorizontetemp
class cl_orcprogramahorizontetemp { 
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
   var $o17_sequencial = 0; 
   var $o17_programa = 0; 
   var $o17_anousu = 0; 
   var $o17_dataini_dia = null; 
   var $o17_dataini_mes = null; 
   var $o17_dataini_ano = null; 
   var $o17_dataini = null; 
   var $o17_datafin_dia = null; 
   var $o17_datafin_mes = null; 
   var $o17_datafin_ano = null; 
   var $o17_datafin = null; 
   var $o17_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o17_sequencial = int4 = Sequencial 
                 o17_programa = int4 = Programa 
                 o17_anousu = int4 = Ano 
                 o17_dataini = date = Data de Início 
                 o17_datafin = date = Data de Témino 
                 o17_valor = float4 = Valor Global Estimado 
                 ";
   //funcao construtor da classe 
   function cl_orcprogramahorizontetemp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprogramahorizontetemp"); 
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
       $this->o17_sequencial = ($this->o17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_sequencial"]:$this->o17_sequencial);
       $this->o17_programa = ($this->o17_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_programa"]:$this->o17_programa);
       $this->o17_anousu = ($this->o17_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_anousu"]:$this->o17_anousu);
       if($this->o17_dataini == ""){
         $this->o17_dataini_dia = ($this->o17_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_dataini_dia"]:$this->o17_dataini_dia);
         $this->o17_dataini_mes = ($this->o17_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_dataini_mes"]:$this->o17_dataini_mes);
         $this->o17_dataini_ano = ($this->o17_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_dataini_ano"]:$this->o17_dataini_ano);
         if($this->o17_dataini_dia != ""){
            $this->o17_dataini = $this->o17_dataini_ano."-".$this->o17_dataini_mes."-".$this->o17_dataini_dia;
         }
       }
       if($this->o17_datafin == ""){
         $this->o17_datafin_dia = ($this->o17_datafin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_datafin_dia"]:$this->o17_datafin_dia);
         $this->o17_datafin_mes = ($this->o17_datafin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_datafin_mes"]:$this->o17_datafin_mes);
         $this->o17_datafin_ano = ($this->o17_datafin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_datafin_ano"]:$this->o17_datafin_ano);
         if($this->o17_datafin_dia != ""){
            $this->o17_datafin = $this->o17_datafin_ano."-".$this->o17_datafin_mes."-".$this->o17_datafin_dia;
         }
       }
       $this->o17_valor = ($this->o17_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_valor"]:$this->o17_valor);
     }else{
       $this->o17_sequencial = ($this->o17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o17_sequencial"]:$this->o17_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o17_sequencial){ 
      $this->atualizacampos();
     if($this->o17_programa == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "o17_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o17_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o17_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o17_dataini == null ){ 
       $this->erro_sql = " Campo Data de Início nao Informado.";
       $this->erro_campo = "o17_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o17_datafin == null ){ 
       $this->erro_sql = " Campo Data de Témino nao Informado.";
       $this->erro_campo = "o17_datafin_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o17_valor == null ){ 
       $this->erro_sql = " Campo Valor Global Estimado nao Informado.";
       $this->erro_campo = "o17_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o17_sequencial == "" || $o17_sequencial == null ){
       $result = db_query("select nextval('orcprogramahorizontetemp_o17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcprogramahorizontetemp_o17_sequencial_seq do campo: o17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcprogramahorizontetemp_o17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o17_sequencial)){
         $this->erro_sql = " Campo o17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o17_sequencial = $o17_sequencial; 
       }
     }
     if(($this->o17_sequencial == null) || ($this->o17_sequencial == "") ){ 
       $this->erro_sql = " Campo o17_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprogramahorizontetemp(
                                       o17_sequencial 
                                      ,o17_programa 
                                      ,o17_anousu 
                                      ,o17_dataini 
                                      ,o17_datafin 
                                      ,o17_valor 
                       )
                values (
                                $this->o17_sequencial 
                               ,$this->o17_programa 
                               ,$this->o17_anousu 
                               ,".($this->o17_dataini == "null" || $this->o17_dataini == ""?"null":"'".$this->o17_dataini."'")." 
                               ,".($this->o17_datafin == "null" || $this->o17_datafin == ""?"null":"'".$this->o17_datafin."'")." 
                               ,$this->o17_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Horizonte Temporal do Programa ($this->o17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Horizonte Temporal do Programa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Horizonte Temporal do Programa ($this->o17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o17_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13652,'$this->o17_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2389,13652,'','".AddSlashes(pg_result($resaco,0,'o17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2389,13653,'','".AddSlashes(pg_result($resaco,0,'o17_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2389,13654,'','".AddSlashes(pg_result($resaco,0,'o17_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2389,13655,'','".AddSlashes(pg_result($resaco,0,'o17_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2389,13656,'','".AddSlashes(pg_result($resaco,0,'o17_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2389,13657,'','".AddSlashes(pg_result($resaco,0,'o17_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orcprogramahorizontetemp set ";
     $virgula = "";
     if(trim($this->o17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o17_sequencial"])){ 
       $sql  .= $virgula." o17_sequencial = $this->o17_sequencial ";
       $virgula = ",";
       if(trim($this->o17_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o17_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o17_programa"])){ 
       $sql  .= $virgula." o17_programa = $this->o17_programa ";
       $virgula = ",";
       if(trim($this->o17_programa) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "o17_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o17_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o17_anousu"])){ 
       $sql  .= $virgula." o17_anousu = $this->o17_anousu ";
       $virgula = ",";
       if(trim($this->o17_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o17_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o17_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o17_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o17_dataini_dia"] !="") ){ 
       $sql  .= $virgula." o17_dataini = '$this->o17_dataini' ";
       $virgula = ",";
       if(trim($this->o17_dataini) == null ){ 
         $this->erro_sql = " Campo Data de Início nao Informado.";
         $this->erro_campo = "o17_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o17_dataini_dia"])){ 
         $sql  .= $virgula." o17_dataini = null ";
         $virgula = ",";
         if(trim($this->o17_dataini) == null ){ 
           $this->erro_sql = " Campo Data de Início nao Informado.";
           $this->erro_campo = "o17_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o17_datafin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o17_datafin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o17_datafin_dia"] !="") ){ 
       $sql  .= $virgula." o17_datafin = '$this->o17_datafin' ";
       $virgula = ",";
       if(trim($this->o17_datafin) == null ){ 
         $this->erro_sql = " Campo Data de Témino nao Informado.";
         $this->erro_campo = "o17_datafin_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o17_datafin_dia"])){ 
         $sql  .= $virgula." o17_datafin = null ";
         $virgula = ",";
         if(trim($this->o17_datafin) == null ){ 
           $this->erro_sql = " Campo Data de Témino nao Informado.";
           $this->erro_campo = "o17_datafin_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o17_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o17_valor"])){ 
       $sql  .= $virgula." o17_valor = $this->o17_valor ";
       $virgula = ",";
       if(trim($this->o17_valor) == null ){ 
         $this->erro_sql = " Campo Valor Global Estimado nao Informado.";
         $this->erro_campo = "o17_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o17_sequencial!=null){
       $sql .= " o17_sequencial = $this->o17_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o17_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13652,'$this->o17_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o17_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2389,13652,'".AddSlashes(pg_result($resaco,$conresaco,'o17_sequencial'))."','$this->o17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o17_programa"]))
           $resac = db_query("insert into db_acount values($acount,2389,13653,'".AddSlashes(pg_result($resaco,$conresaco,'o17_programa'))."','$this->o17_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o17_anousu"]))
           $resac = db_query("insert into db_acount values($acount,2389,13654,'".AddSlashes(pg_result($resaco,$conresaco,'o17_anousu'))."','$this->o17_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o17_dataini"]))
           $resac = db_query("insert into db_acount values($acount,2389,13655,'".AddSlashes(pg_result($resaco,$conresaco,'o17_dataini'))."','$this->o17_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o17_datafin"]))
           $resac = db_query("insert into db_acount values($acount,2389,13656,'".AddSlashes(pg_result($resaco,$conresaco,'o17_datafin'))."','$this->o17_datafin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o17_valor"]))
           $resac = db_query("insert into db_acount values($acount,2389,13657,'".AddSlashes(pg_result($resaco,$conresaco,'o17_valor'))."','$this->o17_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horizonte Temporal do Programa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Horizonte Temporal do Programa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o17_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o17_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13652,'$o17_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2389,13652,'','".AddSlashes(pg_result($resaco,$iresaco,'o17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2389,13653,'','".AddSlashes(pg_result($resaco,$iresaco,'o17_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2389,13654,'','".AddSlashes(pg_result($resaco,$iresaco,'o17_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2389,13655,'','".AddSlashes(pg_result($resaco,$iresaco,'o17_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2389,13656,'','".AddSlashes(pg_result($resaco,$iresaco,'o17_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2389,13657,'','".AddSlashes(pg_result($resaco,$iresaco,'o17_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprogramahorizontetemp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o17_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o17_sequencial = $o17_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Horizonte Temporal do Programa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Horizonte Temporal do Programa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprogramahorizontetemp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprogramahorizontetemp ";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcprogramahorizontetemp.o17_programa and  orcprograma.o54_programa = orcprogramahorizontetemp.o17_anousu";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcprograma.o54_orcorgao and  orcorgao.o40_orgao = orcprograma.o54_anousu";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcprograma.o54_anousu and  orcunidade.o41_orgao = orcprograma.o54_orcorgao and  orcunidade.o41_unidade = orcprograma.o54_orcunidade";
     $sql2 = "";
     if($dbwhere==""){
       if($o17_sequencial!=null ){
         $sql2 .= " where orcprogramahorizontetemp.o17_sequencial = $o17_sequencial "; 
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
   function sql_query_file ( $o17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprogramahorizontetemp ";
     $sql2 = "";
     if($dbwhere==""){
       if($o17_sequencial!=null ){
         $sql2 .= " where orcprogramahorizontetemp.o17_sequencial = $o17_sequencial "; 
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