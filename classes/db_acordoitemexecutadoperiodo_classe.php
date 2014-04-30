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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoitemexecutadoperiodo
class cl_acordoitemexecutadoperiodo { 
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
   var $ac38_sequencial = 0; 
   var $ac38_acordoitemexecutado = 0; 
   var $ac38_acordoitemprevisao = 0; 
   var $ac38_datainicial_dia = null; 
   var $ac38_datainicial_mes = null; 
   var $ac38_datainicial_ano = null; 
   var $ac38_datainicial = null; 
   var $ac38_datafinal_dia = null; 
   var $ac38_datafinal_mes = null; 
   var $ac38_datafinal_ano = null; 
   var $ac38_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac38_sequencial = int4 = Codigo_sequencial 
                 ac38_acordoitemexecutado = int4 = Item 
                 ac38_acordoitemprevisao = int4 = Item previsao 
                 ac38_datainicial = date = Data inicial 
                 ac38_datafinal = date = Data final 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemexecutadoperiodo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemexecutadoperiodo"); 
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
       $this->ac38_sequencial = ($this->ac38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_sequencial"]:$this->ac38_sequencial);
       $this->ac38_acordoitemexecutado = ($this->ac38_acordoitemexecutado == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_acordoitemexecutado"]:$this->ac38_acordoitemexecutado);
       $this->ac38_acordoitemprevisao = ($this->ac38_acordoitemprevisao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_acordoitemprevisao"]:$this->ac38_acordoitemprevisao);
       if($this->ac38_datainicial == ""){
         $this->ac38_datainicial_dia = ($this->ac38_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_datainicial_dia"]:$this->ac38_datainicial_dia);
         $this->ac38_datainicial_mes = ($this->ac38_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_datainicial_mes"]:$this->ac38_datainicial_mes);
         $this->ac38_datainicial_ano = ($this->ac38_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_datainicial_ano"]:$this->ac38_datainicial_ano);
         if($this->ac38_datainicial_dia != ""){
            $this->ac38_datainicial = $this->ac38_datainicial_ano."-".$this->ac38_datainicial_mes."-".$this->ac38_datainicial_dia;
         }
       }
       if($this->ac38_datafinal == ""){
         $this->ac38_datafinal_dia = ($this->ac38_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_datafinal_dia"]:$this->ac38_datafinal_dia);
         $this->ac38_datafinal_mes = ($this->ac38_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_datafinal_mes"]:$this->ac38_datafinal_mes);
         $this->ac38_datafinal_ano = ($this->ac38_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_datafinal_ano"]:$this->ac38_datafinal_ano);
         if($this->ac38_datafinal_dia != ""){
            $this->ac38_datafinal = $this->ac38_datafinal_ano."-".$this->ac38_datafinal_mes."-".$this->ac38_datafinal_dia;
         }
       }
     }else{
       $this->ac38_sequencial = ($this->ac38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac38_sequencial"]:$this->ac38_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac38_sequencial){ 
      $this->atualizacampos();
     if($this->ac38_acordoitemexecutado == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "ac38_acordoitemexecutado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac38_acordoitemprevisao == null ){ 
       $this->erro_sql = " Campo Item previsao nao Informado.";
       $this->erro_campo = "ac38_acordoitemprevisao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac38_datainicial == null ){ 
       $this->erro_sql = " Campo Data inicial nao Informado.";
       $this->erro_campo = "ac38_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac38_datafinal == null ){ 
       $this->erro_sql = " Campo Data final nao Informado.";
       $this->erro_campo = "ac38_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac38_sequencial == "" || $ac38_sequencial == null ){
       $result = db_query("select nextval('acordoitemexecutadoperiodo_ac38_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemexecutadoperiodo_ac38_sequencial_seq do campo: ac38_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac38_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemexecutadoperiodo_ac38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac38_sequencial)){
         $this->erro_sql = " Campo ac38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac38_sequencial = $ac38_sequencial; 
       }
     }
     if(($this->ac38_sequencial == null) || ($this->ac38_sequencial == "") ){ 
       $this->erro_sql = " Campo ac38_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemexecutadoperiodo(
                                       ac38_sequencial 
                                      ,ac38_acordoitemexecutado 
                                      ,ac38_acordoitemprevisao 
                                      ,ac38_datainicial 
                                      ,ac38_datafinal 
                       )
                values (
                                $this->ac38_sequencial 
                               ,$this->ac38_acordoitemexecutado 
                               ,$this->ac38_acordoitemprevisao 
                               ,".($this->ac38_datainicial == "null" || $this->ac38_datainicial == ""?"null":"'".$this->ac38_datainicial."'")." 
                               ,".($this->ac38_datafinal == "null" || $this->ac38_datafinal == ""?"null":"'".$this->ac38_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "acordoitemexecutadoperiodo ($this->ac38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "acordoitemexecutadoperiodo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "acordoitemexecutadoperiodo ($this->ac38_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac38_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18048,'$this->ac38_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3189,18048,'','".AddSlashes(pg_result($resaco,0,'ac38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3189,18049,'','".AddSlashes(pg_result($resaco,0,'ac38_acordoitemexecutado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3189,18050,'','".AddSlashes(pg_result($resaco,0,'ac38_acordoitemprevisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3189,18051,'','".AddSlashes(pg_result($resaco,0,'ac38_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3189,18052,'','".AddSlashes(pg_result($resaco,0,'ac38_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac38_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemexecutadoperiodo set ";
     $virgula = "";
     if(trim($this->ac38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac38_sequencial"])){ 
       $sql  .= $virgula." ac38_sequencial = $this->ac38_sequencial ";
       $virgula = ",";
       if(trim($this->ac38_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo_sequencial nao Informado.";
         $this->erro_campo = "ac38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac38_acordoitemexecutado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac38_acordoitemexecutado"])){ 
       $sql  .= $virgula." ac38_acordoitemexecutado = $this->ac38_acordoitemexecutado ";
       $virgula = ",";
       if(trim($this->ac38_acordoitemexecutado) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "ac38_acordoitemexecutado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac38_acordoitemprevisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac38_acordoitemprevisao"])){ 
       $sql  .= $virgula." ac38_acordoitemprevisao = $this->ac38_acordoitemprevisao ";
       $virgula = ",";
       if(trim($this->ac38_acordoitemprevisao) == null ){ 
         $this->erro_sql = " Campo Item previsao nao Informado.";
         $this->erro_campo = "ac38_acordoitemprevisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac38_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac38_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac38_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ac38_datainicial = '$this->ac38_datainicial' ";
       $virgula = ",";
       if(trim($this->ac38_datainicial) == null ){ 
         $this->erro_sql = " Campo Data inicial nao Informado.";
         $this->erro_campo = "ac38_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_datainicial_dia"])){ 
         $sql  .= $virgula." ac38_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac38_datainicial) == null ){ 
           $this->erro_sql = " Campo Data inicial nao Informado.";
           $this->erro_campo = "ac38_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac38_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac38_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac38_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ac38_datafinal = '$this->ac38_datafinal' ";
       $virgula = ",";
       if(trim($this->ac38_datafinal) == null ){ 
         $this->erro_sql = " Campo Data final nao Informado.";
         $this->erro_campo = "ac38_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_datafinal_dia"])){ 
         $sql  .= $virgula." ac38_datafinal = null ";
         $virgula = ",";
         if(trim($this->ac38_datafinal) == null ){ 
           $this->erro_sql = " Campo Data final nao Informado.";
           $this->erro_campo = "ac38_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ac38_sequencial!=null){
       $sql .= " ac38_sequencial = $this->ac38_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac38_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18048,'$this->ac38_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_sequencial"]) || $this->ac38_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3189,18048,'".AddSlashes(pg_result($resaco,$conresaco,'ac38_sequencial'))."','$this->ac38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_acordoitemexecutado"]) || $this->ac38_acordoitemexecutado != "")
           $resac = db_query("insert into db_acount values($acount,3189,18049,'".AddSlashes(pg_result($resaco,$conresaco,'ac38_acordoitemexecutado'))."','$this->ac38_acordoitemexecutado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_acordoitemprevisao"]) || $this->ac38_acordoitemprevisao != "")
           $resac = db_query("insert into db_acount values($acount,3189,18050,'".AddSlashes(pg_result($resaco,$conresaco,'ac38_acordoitemprevisao'))."','$this->ac38_acordoitemprevisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_datainicial"]) || $this->ac38_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3189,18051,'".AddSlashes(pg_result($resaco,$conresaco,'ac38_datainicial'))."','$this->ac38_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac38_datafinal"]) || $this->ac38_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3189,18052,'".AddSlashes(pg_result($resaco,$conresaco,'ac38_datafinal'))."','$this->ac38_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemexecutadoperiodo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemexecutadoperiodo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac38_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac38_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18048,'$ac38_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3189,18048,'','".AddSlashes(pg_result($resaco,$iresaco,'ac38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3189,18049,'','".AddSlashes(pg_result($resaco,$iresaco,'ac38_acordoitemexecutado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3189,18050,'','".AddSlashes(pg_result($resaco,$iresaco,'ac38_acordoitemprevisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3189,18051,'','".AddSlashes(pg_result($resaco,$iresaco,'ac38_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3189,18052,'','".AddSlashes(pg_result($resaco,$iresaco,'ac38_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemexecutadoperiodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac38_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac38_sequencial = $ac38_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "acordoitemexecutadoperiodo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "acordoitemexecutadoperiodo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemexecutadoperiodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoperiodo ";
     $sql .= "      inner join acordoitemexecutado  on  acordoitemexecutado.ac29_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
     $sql .= "      inner join acordoitemprevisao  on  acordoitemprevisao.ac37_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemprevisao";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemexecutado.ac29_acordoitem";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemprevisao.ac37_acordoitem";
     $sql .= "      inner join acordoposicaoperiodo  on  acordoposicaoperiodo.ac36_sequencial = acordoitemprevisao.ac37_acordoperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac38_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoperiodo.ac38_sequencial = $ac38_sequencial "; 
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
  
  function sql_query_executado ( $ac38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoperiodo ";
     $sql .= "      inner join acordoitemexecutado  on  acordoitemexecutado.ac29_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
     $sql .= "      inner join acordoitemprevisao  on  acordoitemprevisao.ac37_sequencial = acordoitemexecutadoperiodo.ac38_acordoitemprevisao";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemprevisao.ac37_acordoitem";
     $sql .= "      inner join acordoposicaoperiodo  on  acordoposicaoperiodo.ac36_sequencial = acordoitemprevisao.ac37_acordoperiodo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac38_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoperiodo.ac38_sequencial = $ac38_sequencial "; 
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
   function sql_query_file ( $ac38_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemexecutadoperiodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac38_sequencial!=null ){
         $sql2 .= " where acordoitemexecutadoperiodo.ac38_sequencial = $ac38_sequencial "; 
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