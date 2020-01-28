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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meievento
class cl_meievento { 
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
   var $q101_sequencial = 0; 
   var $q101_meigrupoevento = 0; 
   var $q101_codigo = null; 
   var $q101_descricao = null; 
   var $q101_obs = null; 
   var $q101_versao = null; 
   var $q101_dataini_dia = null; 
   var $q101_dataini_mes = null; 
   var $q101_dataini_ano = null; 
   var $q101_dataini = null; 
   var $q101_datafin_dia = null; 
   var $q101_datafin_mes = null; 
   var $q101_datafin_ano = null; 
   var $q101_datafin = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q101_sequencial = int4 = Sequencial 
                 q101_meigrupoevento = int4 = GrupoEvento 
                 q101_codigo = varchar(10) = Código Evento 
                 q101_descricao = varchar(100) = Descrição 
                 q101_obs = text = Obs 
                 q101_versao = varchar(20) = Versão 
                 q101_dataini = date = Data de Início 
                 q101_datafin = date = Data de Finalização 
                 ";
   //funcao construtor da classe 
   function cl_meievento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meievento"); 
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
       $this->q101_sequencial = ($this->q101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_sequencial"]:$this->q101_sequencial);
       $this->q101_meigrupoevento = ($this->q101_meigrupoevento == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_meigrupoevento"]:$this->q101_meigrupoevento);
       $this->q101_codigo = ($this->q101_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_codigo"]:$this->q101_codigo);
       $this->q101_descricao = ($this->q101_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_descricao"]:$this->q101_descricao);
       $this->q101_obs = ($this->q101_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_obs"]:$this->q101_obs);
       $this->q101_versao = ($this->q101_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_versao"]:$this->q101_versao);
       if($this->q101_dataini == ""){
         $this->q101_dataini_dia = ($this->q101_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_dataini_dia"]:$this->q101_dataini_dia);
         $this->q101_dataini_mes = ($this->q101_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_dataini_mes"]:$this->q101_dataini_mes);
         $this->q101_dataini_ano = ($this->q101_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_dataini_ano"]:$this->q101_dataini_ano);
         if($this->q101_dataini_dia != ""){
            $this->q101_dataini = $this->q101_dataini_ano."-".$this->q101_dataini_mes."-".$this->q101_dataini_dia;
         }
       }
       if($this->q101_datafin == ""){
         $this->q101_datafin_dia = ($this->q101_datafin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_datafin_dia"]:$this->q101_datafin_dia);
         $this->q101_datafin_mes = ($this->q101_datafin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_datafin_mes"]:$this->q101_datafin_mes);
         $this->q101_datafin_ano = ($this->q101_datafin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_datafin_ano"]:$this->q101_datafin_ano);
         if($this->q101_datafin_dia != ""){
            $this->q101_datafin = $this->q101_datafin_ano."-".$this->q101_datafin_mes."-".$this->q101_datafin_dia;
         }
       }
     }else{
       $this->q101_sequencial = ($this->q101_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q101_sequencial"]:$this->q101_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q101_sequencial){ 
      $this->atualizacampos();
     if($this->q101_meigrupoevento == null ){ 
       $this->erro_sql = " Campo GrupoEvento nao Informado.";
       $this->erro_campo = "q101_meigrupoevento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q101_codigo == null ){ 
       $this->erro_sql = " Campo Código Evento nao Informado.";
       $this->erro_campo = "q101_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q101_dataini == null ){ 
       $this->q101_dataini = "null";
     }
     if($this->q101_datafin == null ){ 
       $this->q101_datafin = "null";
     }
     if($q101_sequencial == "" || $q101_sequencial == null ){
       $result = db_query("select nextval('meievento_q101_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meievento_q101_sequencial_seq do campo: q101_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q101_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meievento_q101_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q101_sequencial)){
         $this->erro_sql = " Campo q101_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q101_sequencial = $q101_sequencial; 
       }
     }
     if(($this->q101_sequencial == null) || ($this->q101_sequencial == "") ){ 
       $this->erro_sql = " Campo q101_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meievento(
                                       q101_sequencial 
                                      ,q101_meigrupoevento 
                                      ,q101_codigo 
                                      ,q101_descricao 
                                      ,q101_obs 
                                      ,q101_versao 
                                      ,q101_dataini 
                                      ,q101_datafin 
                       )
                values (
                                $this->q101_sequencial 
                               ,$this->q101_meigrupoevento 
                               ,'$this->q101_codigo' 
                               ,'$this->q101_descricao' 
                               ,'$this->q101_obs' 
                               ,'$this->q101_versao' 
                               ,".($this->q101_dataini == "null" || $this->q101_dataini == ""?"null":"'".$this->q101_dataini."'")." 
                               ,".($this->q101_datafin == "null" || $this->q101_datafin == ""?"null":"'".$this->q101_datafin."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Meievento ($this->q101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Meievento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Meievento ($this->q101_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q101_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q101_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15660,'$this->q101_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2746,15660,'','".AddSlashes(pg_result($resaco,0,'q101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,15661,'','".AddSlashes(pg_result($resaco,0,'q101_meigrupoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,16613,'','".AddSlashes(pg_result($resaco,0,'q101_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,15662,'','".AddSlashes(pg_result($resaco,0,'q101_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,15663,'','".AddSlashes(pg_result($resaco,0,'q101_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,15664,'','".AddSlashes(pg_result($resaco,0,'q101_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,15665,'','".AddSlashes(pg_result($resaco,0,'q101_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2746,15666,'','".AddSlashes(pg_result($resaco,0,'q101_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q101_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meievento set ";
     $virgula = "";
     if(trim($this->q101_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_sequencial"])){ 
       $sql  .= $virgula." q101_sequencial = $this->q101_sequencial ";
       $virgula = ",";
       if(trim($this->q101_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q101_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q101_meigrupoevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_meigrupoevento"])){ 
       $sql  .= $virgula." q101_meigrupoevento = $this->q101_meigrupoevento ";
       $virgula = ",";
       if(trim($this->q101_meigrupoevento) == null ){ 
         $this->erro_sql = " Campo GrupoEvento nao Informado.";
         $this->erro_campo = "q101_meigrupoevento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q101_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_codigo"])){ 
       $sql  .= $virgula." q101_codigo = '$this->q101_codigo' ";
       $virgula = ",";
       if(trim($this->q101_codigo) == null ){ 
         $this->erro_sql = " Campo Código Evento nao Informado.";
         $this->erro_campo = "q101_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q101_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_descricao"])){ 
       $sql  .= $virgula." q101_descricao = '$this->q101_descricao' ";
       $virgula = ",";
     }
     if(trim($this->q101_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_obs"])){ 
       $sql  .= $virgula." q101_obs = '$this->q101_obs' ";
       $virgula = ",";
     }
     if(trim($this->q101_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_versao"])){ 
       $sql  .= $virgula." q101_versao = '$this->q101_versao' ";
       $virgula = ",";
     }
     if(trim($this->q101_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q101_dataini_dia"] !="") ){ 
       $sql  .= $virgula." q101_dataini = '$this->q101_dataini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q101_dataini_dia"])){ 
         $sql  .= $virgula." q101_dataini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->q101_datafin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q101_datafin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q101_datafin_dia"] !="") ){ 
       $sql  .= $virgula." q101_datafin = '$this->q101_datafin' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q101_datafin_dia"])){ 
         $sql  .= $virgula." q101_datafin = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($q101_sequencial!=null){
       $sql .= " q101_sequencial = $this->q101_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q101_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15660,'$this->q101_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_sequencial"]) || $this->q101_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2746,15660,'".AddSlashes(pg_result($resaco,$conresaco,'q101_sequencial'))."','$this->q101_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_meigrupoevento"]) || $this->q101_meigrupoevento != "")
           $resac = db_query("insert into db_acount values($acount,2746,15661,'".AddSlashes(pg_result($resaco,$conresaco,'q101_meigrupoevento'))."','$this->q101_meigrupoevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_codigo"]) || $this->q101_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2746,16613,'".AddSlashes(pg_result($resaco,$conresaco,'q101_codigo'))."','$this->q101_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_descricao"]) || $this->q101_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2746,15662,'".AddSlashes(pg_result($resaco,$conresaco,'q101_descricao'))."','$this->q101_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_obs"]) || $this->q101_obs != "")
           $resac = db_query("insert into db_acount values($acount,2746,15663,'".AddSlashes(pg_result($resaco,$conresaco,'q101_obs'))."','$this->q101_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_versao"]) || $this->q101_versao != "")
           $resac = db_query("insert into db_acount values($acount,2746,15664,'".AddSlashes(pg_result($resaco,$conresaco,'q101_versao'))."','$this->q101_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_dataini"]) || $this->q101_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2746,15665,'".AddSlashes(pg_result($resaco,$conresaco,'q101_dataini'))."','$this->q101_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q101_datafin"]) || $this->q101_datafin != "")
           $resac = db_query("insert into db_acount values($acount,2746,15666,'".AddSlashes(pg_result($resaco,$conresaco,'q101_datafin'))."','$this->q101_datafin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Meievento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Meievento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q101_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q101_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15660,'$q101_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2746,15660,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,15661,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_meigrupoevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,16613,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,15662,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,15663,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,15664,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,15665,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2746,15666,'','".AddSlashes(pg_result($resaco,$iresaco,'q101_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meievento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q101_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q101_sequencial = $q101_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Meievento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q101_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Meievento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q101_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q101_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meievento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meievento ";
     $sql .= "      inner join meigrupoevento  on  meigrupoevento.q100_sequencial = meievento.q101_meigrupoevento";
     $sql2 = "";
     if($dbwhere==""){
       if($q101_sequencial!=null ){
         $sql2 .= " where meievento.q101_sequencial = $q101_sequencial "; 
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
   function sql_query_file ( $q101_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meievento ";
     $sql2 = "";
     if($dbwhere==""){
       if($q101_sequencial!=null ){
         $sql2 .= " where meievento.q101_sequencial = $q101_sequencial "; 
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