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
//CLASSE DA ENTIDADE meiimportalinhaatividademeievento
class cl_meiimportalinhaatividademeievento { 
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
   var $q111_meiimportalinhaatividade = 0; 
   var $q111_meievento = 0; 
   var $q111_data_dia = null; 
   var $q111_data_mes = null; 
   var $q111_data_ano = null; 
   var $q111_data = null; 
   var $q111_processado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q111_sequencial = int4 = Sequencial 
                 q111_meiimportalinhaatividade = int4 = Importação do MEI por Atividade 
                 q111_meievento = int4 = Eventos do MEI 
                 q111_data = date = Data do Evento 
                 q111_processado = bool = Processado 
                 ";
   //funcao construtor da classe 
   function cl_meiimportalinhaatividademeievento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportalinhaatividademeievento"); 
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
       $this->q111_meiimportalinhaatividade = ($this->q111_meiimportalinhaatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meiimportalinhaatividade"]:$this->q111_meiimportalinhaatividade);
       $this->q111_meievento = ($this->q111_meievento == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_meievento"]:$this->q111_meievento);
       if($this->q111_data == ""){
         $this->q111_data_dia = ($this->q111_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_data_dia"]:$this->q111_data_dia);
         $this->q111_data_mes = ($this->q111_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_data_mes"]:$this->q111_data_mes);
         $this->q111_data_ano = ($this->q111_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_data_ano"]:$this->q111_data_ano);
         if($this->q111_data_dia != ""){
            $this->q111_data = $this->q111_data_ano."-".$this->q111_data_mes."-".$this->q111_data_dia;
         }
       }
       $this->q111_processado = ($this->q111_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["q111_processado"]:$this->q111_processado);
     }else{
       $this->q111_sequencial = ($this->q111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q111_sequencial"]:$this->q111_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q111_sequencial){ 
      $this->atualizacampos();
     if($this->q111_meiimportalinhaatividade == null ){ 
       $this->erro_sql = " Campo Importação do MEI por Atividade nao Informado.";
       $this->erro_campo = "q111_meiimportalinhaatividade";
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
     if($this->q111_data == null ){ 
       $this->erro_sql = " Campo Data do Evento nao Informado.";
       $this->erro_campo = "q111_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q111_processado == null ){ 
       $this->erro_sql = " Campo Processado nao Informado.";
       $this->erro_campo = "q111_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q111_sequencial == "" || $q111_sequencial == null ){
       $result = db_query("select nextval('meiimportalinhaatividademeievento_q111_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportalinhaatividademeievento_q111_sequencial_seq do campo: q111_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q111_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportalinhaatividademeievento_q111_sequencial_seq");
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
     $sql = "insert into meiimportalinhaatividademeievento(
                                       q111_sequencial 
                                      ,q111_meiimportalinhaatividade 
                                      ,q111_meievento 
                                      ,q111_data 
                                      ,q111_processado 
                       )
                values (
                                $this->q111_sequencial 
                               ,$this->q111_meiimportalinhaatividade 
                               ,$this->q111_meievento 
                               ,".($this->q111_data == "null" || $this->q111_data == ""?"null":"'".$this->q111_data."'")." 
                               ,'$this->q111_processado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Eventos da Importação do MEI por Atividade ($this->q111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Eventos da Importação do MEI por Atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Eventos da Importação do MEI por Atividade ($this->q111_sequencial) nao Incluído. Inclusao Abortada.";
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
       $resac = db_query("insert into db_acountkey values($acount,16244,'$this->q111_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2850,16244,'','".AddSlashes(pg_result($resaco,0,'q111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2850,16245,'','".AddSlashes(pg_result($resaco,0,'q111_meiimportalinhaatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2850,16246,'','".AddSlashes(pg_result($resaco,0,'q111_meievento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2850,16247,'','".AddSlashes(pg_result($resaco,0,'q111_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2850,16248,'','".AddSlashes(pg_result($resaco,0,'q111_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q111_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportalinhaatividademeievento set ";
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
     if(trim($this->q111_meiimportalinhaatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportalinhaatividade"])){ 
       $sql  .= $virgula." q111_meiimportalinhaatividade = $this->q111_meiimportalinhaatividade ";
       $virgula = ",";
       if(trim($this->q111_meiimportalinhaatividade) == null ){ 
         $this->erro_sql = " Campo Importação do MEI por Atividade nao Informado.";
         $this->erro_campo = "q111_meiimportalinhaatividade";
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
     if(trim($this->q111_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q111_data_dia"] !="") ){ 
       $sql  .= $virgula." q111_data = '$this->q111_data' ";
       $virgula = ",";
       if(trim($this->q111_data) == null ){ 
         $this->erro_sql = " Campo Data do Evento nao Informado.";
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
           $this->erro_sql = " Campo Data do Evento nao Informado.";
           $this->erro_campo = "q111_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q111_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q111_processado"])){ 
       $sql  .= $virgula." q111_processado = '$this->q111_processado' ";
       $virgula = ",";
       if(trim($this->q111_processado) == null ){ 
         $this->erro_sql = " Campo Processado nao Informado.";
         $this->erro_campo = "q111_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
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
         $resac = db_query("insert into db_acountkey values($acount,16244,'$this->q111_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_sequencial"]) || $this->q111_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2850,16244,'".AddSlashes(pg_result($resaco,$conresaco,'q111_sequencial'))."','$this->q111_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meiimportalinhaatividade"]) || $this->q111_meiimportalinhaatividade != "")
           $resac = db_query("insert into db_acount values($acount,2850,16245,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meiimportalinhaatividade'))."','$this->q111_meiimportalinhaatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_meievento"]) || $this->q111_meievento != "")
           $resac = db_query("insert into db_acount values($acount,2850,16246,'".AddSlashes(pg_result($resaco,$conresaco,'q111_meievento'))."','$this->q111_meievento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_data"]) || $this->q111_data != "")
           $resac = db_query("insert into db_acount values($acount,2850,16247,'".AddSlashes(pg_result($resaco,$conresaco,'q111_data'))."','$this->q111_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q111_processado"]) || $this->q111_processado != "")
           $resac = db_query("insert into db_acount values($acount,2850,16248,'".AddSlashes(pg_result($resaco,$conresaco,'q111_processado'))."','$this->q111_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos da Importação do MEI por Atividade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Eventos da Importação do MEI por Atividade nao foi Alterado. Alteracao Executada.\\n";
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
         $resac = db_query("insert into db_acountkey values($acount,16244,'$q111_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2850,16244,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2850,16245,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meiimportalinhaatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2850,16246,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_meievento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2850,16247,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2850,16248,'','".AddSlashes(pg_result($resaco,$iresaco,'q111_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportalinhaatividademeievento
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
       $this->erro_sql   = "Eventos da Importação do MEI por Atividade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Eventos da Importação do MEI por Atividade nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportalinhaatividademeievento";
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
     $sql .= " from meiimportalinhaatividademeievento ";
     $sql .= "      inner join meievento  on  meievento.q101_sequencial = meiimportalinhaatividademeievento.q111_meievento";
     $sql .= "      inner join meiimportalinhaatividade  on  meiimportalinhaatividade.q106_sequencial = meiimportalinhaatividademeievento.q111_meiimportalinhaatividade";
     $sql .= "      inner join meigrupoevento  on  meigrupoevento.q100_sequencial = meievento.q101_meigrupoevento";
     $sql .= "      inner join meiimportalinha  as a on   a.q105_sequencial = meiimportalinhaatividade.q106_meiimportalinha";
     $sql2 = "";
     if($dbwhere==""){
       if($q111_sequencial!=null ){
         $sql2 .= " where meiimportalinhaatividademeievento.q111_sequencial = $q111_sequencial "; 
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
     $sql .= " from meiimportalinhaatividademeievento ";
     $sql2 = "";
     if($dbwhere==""){
       if($q111_sequencial!=null ){
         $sql2 .= " where meiimportalinhaatividademeievento.q111_sequencial = $q111_sequencial "; 
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