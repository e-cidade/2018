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
//CLASSE DA ENTIDADE meiimportameireg
class cl_meiimportameireg { 
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
   var $q111_sequencial = 0; 
   var $q111_meiimportamei = 0; 
   var $q111_meievento = 0; 
   var $q111_meiimportameiregatividade = 0; 
   var $q111_meiimportameiregempresa = 0; 
   var $q111_meiimportameiregcontador = 0; 
   var $q111_meiimportameiregresponsavel = 0; 
   var $q111_data_dia = null; 
   var $q111_data_mes = null; 
   var $q111_data_ano = null; 
   var $q111_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q111_sequencial = int4 = Sequencial 
                 q111_meiimportamei = int4 = Importação do MEI 
                 q111_meievento = int4 = Eventos do MEI 
                 q111_meiimportameiregatividade = int4 = Registro de Atividade do MEI 
                 q111_meiimportameiregempresa = int4 = Registro de Empresa do MEI 
                 q111_meiimportameiregcontador = int4 = Registro de Contador do MEI 
                 q111_meiimportameiregresponsavel = int4 = Registro de Responsavel do MEI 
                 q111_data = date = Data Evento 
                 ";
   //funcao construtor da classe 
   function cl_meiimportameireg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportameireg"); 
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
       $this->q111_sequencial = ($this->q111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_sequencial"]:$this->q111_sequencial);
       $this->q111_meiimportamei = ($this->q111_meiimportamei == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meiimportamei"]:$this->q111_meiimportamei);
       $this->q111_meievento = ($this->q111_meievento == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meievento"]:$this->q111_meievento);
       $this->q111_meiimportameiregatividade = ($this->q111_meiimportameiregatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregatividade"]:$this->q111_meiimportameiregatividade);
       $this->q111_meiimportameiregempresa = ($this->q111_meiimportameiregempresa == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregempresa"]:$this->q111_meiimportameiregempresa);
       $this->q111_meiimportameiregcontador = ($this->q111_meiimportameiregcontador == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregcontador"]:$this->q111_meiimportameiregcontador);
       $this->q111_meiimportameiregresponsavel = ($this->q111_meiimportameiregresponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregresponsavel"]:$this->q111_meiimportameiregresponsavel);
       if($this->q111_data == ""){
         $this->q111_data_dia = ($this->q111_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_data_dia"]:$this->q111_data_dia);
         $this->q111_data_mes = ($this->q111_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_data_mes"]:$this->q111_data_mes);
         $this->q111_data_ano = ($this->q111_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_data_ano"]:$this->q111_data_ano);
         if($this->q111_data_dia != ""){
            $this->q111_data = $this->q111_data_ano."-".$this->q111_data_mes."-".$this->q111_data_dia;
         }
       }
     }else{
       $this->q111_sequencial = ($this->q111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_sequencial"]:$this->q111_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q111_sequencial){ 
      $this->atualizacampos();
     if($this->q111_meiimportamei == null ){ 
       $this->erro_sql = " Campo Importação do MEI nao Informado.";
       $this->erro_campo = "q111_meiimportamei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q111_meievento == null ){ 
       $this->erro_sql = " Campo Eventos do MEI nao Informado.";
       $this->erro_campo = "q111_meievento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q111_meiimportameiregatividade == null ){ 
       $this->q111_meiimportameiregatividade = "null";
     }
     if($this->q111_meiimportameiregempresa == null ){ 
       $this->q111_meiimportameiregempresa = "null";
     }
     if($this->q111_meiimportameiregcontador == null ){ 
       $this->q111_meiimportameiregcontador = "null";
     }
     if($this->q111_meiimportameiregresponsavel == null ){ 
       $this->q111_meiimportameiregresponsavel = "null";
     }
     if($this->q111_data == null ){ 
       $this->erro_sql = " Campo Data Evento nao Informado.";
       $this->erro_campo = "q111_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q111_sequencial == "" || $q111_sequencial == null ){
       $result = db_query("select nextval('meiimportameireg_q111_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportameireg_q111_sequencial_seq do campo: q111_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q111_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportameireg_q111_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q111_sequencial)){
         $this->erro_sql = " Campo q111_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q111_sequencial = $q111_sequencial; 
       }
     }
     if(($this->q111_sequencial == null) || ($this->q111_sequencial == "") ){ 
       $this->erro_sql = " Campo q111_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportameireg(
                                       q111_sequencial 
                                      ,q111_meiimportamei 
                                      ,q111_meievento 
                                      ,q111_meiimportameiregatividade 
                                      ,q111_meiimportameiregempresa 
                                      ,q111_meiimportameiregcontador 
                                      ,q111_meiimportameiregresponsavel 
                                      ,q111_data 
                       )
                values (
                                $this->q111_sequencial 
                               ,$this->q111_meiimportamei 
                               ,$this->q111_meievento 
                               ,$this->q111_meiimportameiregatividade 
                               ,$this->q111_meiimportameiregempresa 
                               ,$this->q111_meiimportameiregcontador 
                               ,$this->q111_meiimportameiregresponsavel 
                               ,".($this->q111_data == "null" || $this->q111_data == ""?"null":"'".$this->q111_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro de Importação do MEI  ($this->q111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro de Importação do MEI  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro de Importação do MEI  ($this->q111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q111_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q111_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16595,'$this->q111_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2915,16595,'','".AddSlashes(pg_result($resaco,0,'q111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16608,'','".AddSlashes(pg_result($resaco,0,'q111_meiimportamei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16596,'','".AddSlashes(pg_result($resaco,0,'q111_meievento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16599,'','".AddSlashes(pg_result($resaco,0,'q111_meiimportameiregatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16600,'','".AddSlashes(pg_result($resaco,0,'q111_meiimportameiregempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16601,'','".AddSlashes(pg_result($resaco,0,'q111_meiimportameiregcontador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16602,'','".AddSlashes(pg_result($resaco,0,'q111_meiimportameiregresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2915,16603,'','".AddSlashes(pg_result($resaco,0,'q111_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q111_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportameireg set ";
     $virgula = "";
     if(trim($this->q111_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_sequencial"])){ 
       $sql  .= $virgula." q111_sequencial = $this->q111_sequencial ";
       $virgula = ",";
       if(trim($this->q111_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q111_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q111_meiimportamei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportamei"])){ 
       $sql  .= $virgula." q111_meiimportamei = $this->q111_meiimportamei ";
       $virgula = ",";
       if(trim($this->q111_meiimportamei) == null ){ 
         $this->erro_sql = " Campo Importação do MEI nao Informado.";
         $this->erro_campo = "q111_meiimportamei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q111_meievento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meievento"])){ 
       $sql  .= $virgula." q111_meievento = $this->q111_meievento ";
       $virgula = ",";
       if(trim($this->q111_meievento) == null ){ 
         $this->erro_sql = " Campo Eventos do MEI nao Informado.";
         $this->erro_campo = "q111_meievento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q111_meiimportameiregatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregatividade"])){ 
        if(trim($this->q111_meiimportameiregatividade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregatividade"])){ 
           $this->q111_meiimportameiregatividade = "null" ; 
        } 
       $sql  .= $virgula." q111_meiimportameiregatividade = $this->q111_meiimportameiregatividade ";
       $virgula = ",";
     }
     if(trim($this->q111_meiimportameiregempresa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregempresa"])){ 
        if(trim($this->q111_meiimportameiregempresa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregempresa"])){ 
           $this->q111_meiimportameiregempresa = "null" ; 
        } 
       $sql  .= $virgula." q111_meiimportameiregempresa = $this->q111_meiimportameiregempresa ";
       $virgula = ",";
     }
     if(trim($this->q111_meiimportameiregcontador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregcontador"])){ 
        if(trim($this->q111_meiimportameiregcontador)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregcontador"])){ 
           $this->q111_meiimportameiregcontador = "null" ; 
        } 
       $sql  .= $virgula." q111_meiimportameiregcontador = $this->q111_meiimportameiregcontador ";
       $virgula = ",";
     }
     if(trim($this->q111_meiimportameiregresponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregresponsavel"])){ 
        if(trim($this->q111_meiimportameiregresponsavel)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregresponsavel"])){ 
           $this->q111_meiimportameiregresponsavel = "null" ; 
        } 
       $sql  .= $virgula." q111_meiimportameiregresponsavel = $this->q111_meiimportameiregresponsavel ";
       $virgula = ",";
     }
     if(trim($this->q111_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q111_data_dia"] !="") ){ 
       $sql  .= $virgula." q111_data = '$this->q111_data' ";
       $virgula = ",";
       if(trim($this->q111_data) == null ){ 
         $this->erro_sql = " Campo Data Evento nao Informado.";
         $this->erro_campo = "q111_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q111_data_dia"])){ 
         $sql  .= $virgula." q111_data = null ";
         $virgula = ",";
         if(trim($this->q111_data) == null ){ 
           $this->erro_sql = " Campo Data Evento nao Informado.";
           $this->erro_campo = "q111_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($q111_sequencial!=null){
       $sql .= " q111_sequencial = $this->q111_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q111_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16595,'$this->q111_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_sequencial"]) || $this->q111_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2915,16595,'".AddSlashes(pg_result($resaco,$conresaco,'q111_sequencial'))."','$this->q111_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportamei"]) || $this->q111_meiimportamei != "")
           $resac = db_query("insert into db_acount values($acount,2915,16608,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meiimportamei'))."','$this->q111_meiimportamei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meievento"]) || $this->q111_meievento != "")
           $resac = db_query("insert into db_acount values($acount,2915,16596,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meievento'))."','$this->q111_meievento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregatividade"]) || $this->q111_meiimportameiregatividade != "")
           $resac = db_query("insert into db_acount values($acount,2915,16599,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meiimportameiregatividade'))."','$this->q111_meiimportameiregatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregempresa"]) || $this->q111_meiimportameiregempresa != "")
           $resac = db_query("insert into db_acount values($acount,2915,16600,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meiimportameiregempresa'))."','$this->q111_meiimportameiregempresa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregcontador"]) || $this->q111_meiimportameiregcontador != "")
           $resac = db_query("insert into db_acount values($acount,2915,16601,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meiimportameiregcontador'))."','$this->q111_meiimportameiregcontador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportameiregresponsavel"]) || $this->q111_meiimportameiregresponsavel != "")
           $resac = db_query("insert into db_acount values($acount,2915,16602,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meiimportameiregresponsavel'))."','$this->q111_meiimportameiregresponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_data"]) || $this->q111_data != "")
           $resac = db_query("insert into db_acount values($acount,2915,16603,'".AddSlashes(pg_result($resaco,$conresaco,'q111_data'))."','$this->q111_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Importação do MEI  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Importação do MEI  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q111_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q111_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16595,'$q111_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2915,16595,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16608,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meiimportamei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16596,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meievento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16599,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meiimportameiregatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16600,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meiimportameiregempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16601,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meiimportameiregcontador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16602,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meiimportameiregresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2915,16603,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportameireg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q111_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q111_sequencial = $q111_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro de Importação do MEI  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro de Importação do MEI  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q111_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportameireg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameireg ";
     $sql .= "      inner join meievento  on  meievento.q101_sequencial = meiimportameireg.q111_meievento";
     $sql .= "      inner join meiimportamei  on  meiimportamei.q105_sequencial = meiimportameireg.q111_meiimportamei";
     $sql .= "      left  join meiimportameiregatividade  on  meiimportameiregatividade.q106_sequencial = meiimportameireg.q111_meiimportameiregatividade";
     $sql .= "      left  join meiimportameiregempresa  on  meiimportameiregempresa.q107_sequencial = meiimportameireg.q111_meiimportameiregempresa";
     $sql .= "      left  join meiimportameiregresponsavel  on  meiimportameiregresponsavel.q108_sequencial = meiimportameireg.q111_meiimportameiregresponsavel";
     $sql .= "      left  join meiimportameiregcontador  on  meiimportameiregcontador.q109_sequencial = meiimportameireg.q111_meiimportameiregcontador";
     $sql .= "      inner join meigrupoevento  on  meigrupoevento.q100_sequencial = meievento.q101_meigrupoevento";
     $sql .= "      inner join meiimporta  as a on   a.q104_sequencial = meiimportamei.q105_meiimporta";
     $sql2 = "";
     if($dbwhere==""){
       if($q111_sequencial!=null ){
         $sql2 .= " where meiimportameireg.q111_sequencial = $q111_sequencial "; 
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
   function sql_query_file ( $q111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportameireg ";
     $sql2 = "";
     if($dbwhere==""){
       if($q111_sequencial!=null ){
         $sql2 .= " where meiimportameireg.q111_sequencial = $q111_sequencial "; 
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