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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitgrupoprograma
class cl_habitgrupoprograma { 
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
   var $ht03_sequencial = 0; 
   var $ht03_habittipogrupoprograma = 0; 
   var $ht03_descricao = null; 
   var $ht03_obs = null; 
   var $ht03_datainicial_dia = null; 
   var $ht03_datainicial_mes = null; 
   var $ht03_datainicial_ano = null; 
   var $ht03_datainicial = null; 
   var $ht03_datafinal_dia = null; 
   var $ht03_datafinal_mes = null; 
   var $ht03_datafinal_ano = null; 
   var $ht03_datafinal = null; 
   var $ht03_resppagamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht03_sequencial = int4 = Sequencial 
                 ht03_habittipogrupoprograma = int4 = Tipo de Grupo 
                 ht03_descricao = varchar(50) = Descrição 
                 ht03_obs = text = Observação 
                 ht03_datainicial = date = Data Inicial 
                 ht03_datafinal = date = Data Final 
                 ht03_resppagamento = int4 = Responsável Pagamento 
                 ";
   //funcao construtor da classe 
   function cl_habitgrupoprograma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitgrupoprograma"); 
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
       $this->ht03_sequencial = ($this->ht03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_sequencial"]:$this->ht03_sequencial);
       $this->ht03_habittipogrupoprograma = ($this->ht03_habittipogrupoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_habittipogrupoprograma"]:$this->ht03_habittipogrupoprograma);
       $this->ht03_descricao = ($this->ht03_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_descricao"]:$this->ht03_descricao);
       $this->ht03_obs = ($this->ht03_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_obs"]:$this->ht03_obs);
       if($this->ht03_datainicial == ""){
         $this->ht03_datainicial_dia = ($this->ht03_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_datainicial_dia"]:$this->ht03_datainicial_dia);
         $this->ht03_datainicial_mes = ($this->ht03_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_datainicial_mes"]:$this->ht03_datainicial_mes);
         $this->ht03_datainicial_ano = ($this->ht03_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_datainicial_ano"]:$this->ht03_datainicial_ano);
         if($this->ht03_datainicial_dia != ""){
            $this->ht03_datainicial = $this->ht03_datainicial_ano."-".$this->ht03_datainicial_mes."-".$this->ht03_datainicial_dia;
         }
       }
       if($this->ht03_datafinal == ""){
         $this->ht03_datafinal_dia = ($this->ht03_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_datafinal_dia"]:$this->ht03_datafinal_dia);
         $this->ht03_datafinal_mes = ($this->ht03_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_datafinal_mes"]:$this->ht03_datafinal_mes);
         $this->ht03_datafinal_ano = ($this->ht03_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_datafinal_ano"]:$this->ht03_datafinal_ano);
         if($this->ht03_datafinal_dia != ""){
            $this->ht03_datafinal = $this->ht03_datafinal_ano."-".$this->ht03_datafinal_mes."-".$this->ht03_datafinal_dia;
         }
       }
       $this->ht03_resppagamento = ($this->ht03_resppagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_resppagamento"]:$this->ht03_resppagamento);
     }else{
       $this->ht03_sequencial = ($this->ht03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht03_sequencial"]:$this->ht03_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht03_sequencial){ 
      $this->atualizacampos();
     if($this->ht03_habittipogrupoprograma == null ){ 
       $this->erro_sql = " Campo Tipo de Grupo nao Informado.";
       $this->erro_campo = "ht03_habittipogrupoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht03_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ht03_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht03_datainicial == null ){ 
       $this->ht03_datainicial = "null";
     }
     if($this->ht03_datafinal == null ){ 
       $this->ht03_datafinal = "null";
     }
     if($this->ht03_resppagamento == null ){ 
       $this->erro_sql = " Campo Responsável Pagamento nao Informado.";
       $this->erro_campo = "ht03_resppagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht03_sequencial == "" || $ht03_sequencial == null ){
       $result = db_query("select nextval('habitgrupoprograma_ht03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitgrupoprograma_ht03_sequencial_seq do campo: ht03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitgrupoprograma_ht03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht03_sequencial)){
         $this->erro_sql = " Campo ht03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht03_sequencial = $ht03_sequencial; 
       }
     }
     if(($this->ht03_sequencial == null) || ($this->ht03_sequencial == "") ){ 
       $this->erro_sql = " Campo ht03_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitgrupoprograma(
                                       ht03_sequencial 
                                      ,ht03_habittipogrupoprograma 
                                      ,ht03_descricao 
                                      ,ht03_obs 
                                      ,ht03_datainicial 
                                      ,ht03_datafinal 
                                      ,ht03_resppagamento 
                       )
                values (
                                $this->ht03_sequencial 
                               ,$this->ht03_habittipogrupoprograma 
                               ,'$this->ht03_descricao' 
                               ,'$this->ht03_obs' 
                               ,".($this->ht03_datainicial == "null" || $this->ht03_datainicial == ""?"null":"'".$this->ht03_datainicial."'")." 
                               ,".($this->ht03_datafinal == "null" || $this->ht03_datafinal == ""?"null":"'".$this->ht03_datafinal."'")." 
                               ,$this->ht03_resppagamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Grupo de Programa da Habitação ($this->ht03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Grupo de Programa da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Grupo de Programa da Habitação ($this->ht03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht03_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16956,'$this->ht03_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2991,16956,'','".AddSlashes(pg_result($resaco,0,'ht03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2991,16957,'','".AddSlashes(pg_result($resaco,0,'ht03_habittipogrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2991,16958,'','".AddSlashes(pg_result($resaco,0,'ht03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2991,16959,'','".AddSlashes(pg_result($resaco,0,'ht03_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2991,16960,'','".AddSlashes(pg_result($resaco,0,'ht03_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2991,16961,'','".AddSlashes(pg_result($resaco,0,'ht03_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2991,16962,'','".AddSlashes(pg_result($resaco,0,'ht03_resppagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht03_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitgrupoprograma set ";
     $virgula = "";
     if(trim($this->ht03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_sequencial"])){ 
       $sql  .= $virgula." ht03_sequencial = $this->ht03_sequencial ";
       $virgula = ",";
       if(trim($this->ht03_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht03_habittipogrupoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_habittipogrupoprograma"])){ 
       $sql  .= $virgula." ht03_habittipogrupoprograma = $this->ht03_habittipogrupoprograma ";
       $virgula = ",";
       if(trim($this->ht03_habittipogrupoprograma) == null ){ 
         $this->erro_sql = " Campo Tipo de Grupo nao Informado.";
         $this->erro_campo = "ht03_habittipogrupoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht03_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_descricao"])){ 
       $sql  .= $virgula." ht03_descricao = '$this->ht03_descricao' ";
       $virgula = ",";
       if(trim($this->ht03_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ht03_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht03_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_obs"])){ 
       $sql  .= $virgula." ht03_obs = '$this->ht03_obs' ";
       $virgula = ",";
     }
     if(trim($this->ht03_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht03_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ht03_datainicial = '$this->ht03_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_datainicial_dia"])){ 
         $sql  .= $virgula." ht03_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht03_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht03_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ht03_datafinal = '$this->ht03_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_datafinal_dia"])){ 
         $sql  .= $virgula." ht03_datafinal = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht03_resppagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht03_resppagamento"])){ 
       $sql  .= $virgula." ht03_resppagamento = $this->ht03_resppagamento ";
       $virgula = ",";
       if(trim($this->ht03_resppagamento) == null ){ 
         $this->erro_sql = " Campo Responsável Pagamento nao Informado.";
         $this->erro_campo = "ht03_resppagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht03_sequencial!=null){
       $sql .= " ht03_sequencial = $this->ht03_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht03_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16956,'$this->ht03_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_sequencial"]) || $this->ht03_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2991,16956,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_sequencial'))."','$this->ht03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_habittipogrupoprograma"]) || $this->ht03_habittipogrupoprograma != "")
           $resac = db_query("insert into db_acount values($acount,2991,16957,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_habittipogrupoprograma'))."','$this->ht03_habittipogrupoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_descricao"]) || $this->ht03_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2991,16958,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_descricao'))."','$this->ht03_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_obs"]) || $this->ht03_obs != "")
           $resac = db_query("insert into db_acount values($acount,2991,16959,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_obs'))."','$this->ht03_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_datainicial"]) || $this->ht03_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2991,16960,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_datainicial'))."','$this->ht03_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_datafinal"]) || $this->ht03_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2991,16961,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_datafinal'))."','$this->ht03_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht03_resppagamento"]) || $this->ht03_resppagamento != "")
           $resac = db_query("insert into db_acount values($acount,2991,16962,'".AddSlashes(pg_result($resaco,$conresaco,'ht03_resppagamento'))."','$this->ht03_resppagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Grupo de Programa da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Grupo de Programa da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht03_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht03_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16956,'$ht03_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2991,16956,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2991,16957,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_habittipogrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2991,16958,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2991,16959,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2991,16960,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2991,16961,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2991,16962,'','".AddSlashes(pg_result($resaco,$iresaco,'ht03_resppagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitgrupoprograma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht03_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht03_sequencial = $ht03_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Grupo de Programa da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Grupo de Programa da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitgrupoprograma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitgrupoprograma ";
     $sql .= "      inner join habittipogrupoprograma  on  habittipogrupoprograma.ht02_sequencial = habitgrupoprograma.ht03_habittipogrupoprograma";
     $sql2 = "";
     if($dbwhere==""){
       if($ht03_sequencial!=null ){
         $sql2 .= " where habitgrupoprograma.ht03_sequencial = $ht03_sequencial "; 
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
   function sql_query_file ( $ht03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitgrupoprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht03_sequencial!=null ){
         $sql2 .= " where habitgrupoprograma.ht03_sequencial = $ht03_sequencial "; 
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