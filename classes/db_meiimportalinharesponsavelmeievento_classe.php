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
//CLASSE DA ENTIDADE meiimportalinharesponsavelmeievento
class cl_meiimportalinharesponsavelmeievento { 
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
   var $q113_sequencial = 0; 
   var $q113_meiimportalinharesponsavel = 0; 
   var $q113_meievento = 0; 
   var $q113_data_dia = null; 
   var $q113_data_mes = null; 
   var $q113_data_ano = null; 
   var $q113_data = null; 
   var $q113_processado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q113_sequencial = int4 = Sequencial 
                 q113_meiimportalinharesponsavel = int4 = Importação do MEI por Resposável 
                 q113_meievento = int4 = Eventos do MEI 
                 q113_data = date = Data do Evento 
                 q113_processado = bool = Processado 
                 ";
   //funcao construtor da classe 
   function cl_meiimportalinharesponsavelmeievento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimportalinharesponsavelmeievento"); 
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
       $this->q113_sequencial = ($this->q113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_sequencial"]:$this->q113_sequencial);
       $this->q113_meiimportalinharesponsavel = ($this->q113_meiimportalinharesponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_meiimportalinharesponsavel"]:$this->q113_meiimportalinharesponsavel);
       $this->q113_meievento = ($this->q113_meievento == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_meievento"]:$this->q113_meievento);
       if($this->q113_data == ""){
         $this->q113_data_dia = ($this->q113_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_data_dia"]:$this->q113_data_dia);
         $this->q113_data_mes = ($this->q113_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_data_mes"]:$this->q113_data_mes);
         $this->q113_data_ano = ($this->q113_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_data_ano"]:$this->q113_data_ano);
         if($this->q113_data_dia != ""){
            $this->q113_data = $this->q113_data_ano."-".$this->q113_data_mes."-".$this->q113_data_dia;
         }
       }
       $this->q113_processado = ($this->q113_processado == "f"?@$GLOBALS["HTTP_POST_VARS"]["q113_processado"]:$this->q113_processado);
     }else{
       $this->q113_sequencial = ($this->q113_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q113_sequencial"]:$this->q113_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q113_sequencial){ 
      $this->atualizacampos();
     if($this->q113_meiimportalinharesponsavel == null ){ 
       $this->erro_sql = " Campo Importação do MEI por Resposável nao Informado.";
       $this->erro_campo = "q113_meiimportalinharesponsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q113_meievento == null ){ 
       $this->erro_sql = " Campo Eventos do MEI nao Informado.";
       $this->erro_campo = "q113_meievento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q113_data == null ){ 
       $this->erro_sql = " Campo Data do Evento nao Informado.";
       $this->erro_campo = "q113_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q113_processado == null ){ 
       $this->erro_sql = " Campo Processado nao Informado.";
       $this->erro_campo = "q113_processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q113_sequencial == "" || $q113_sequencial == null ){
       $result = db_query("select nextval('meiimportalinharesponsavelmeievento_q113_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimportalinharesponsavelmeievento_q113_sequencial_seq do campo: q113_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q113_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimportalinharesponsavelmeievento_q113_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q113_sequencial)){
         $this->erro_sql = " Campo q113_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q113_sequencial = $q113_sequencial; 
       }
     }
     if(($this->q113_sequencial == null) || ($this->q113_sequencial == "") ){ 
       $this->erro_sql = " Campo q113_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimportalinharesponsavelmeievento(
                                       q113_sequencial 
                                      ,q113_meiimportalinharesponsavel 
                                      ,q113_meievento 
                                      ,q113_data 
                                      ,q113_processado 
                       )
                values (
                                $this->q113_sequencial 
                               ,$this->q113_meiimportalinharesponsavel 
                               ,$this->q113_meievento 
                               ,".($this->q113_data == "null" || $this->q113_data == ""?"null":"'".$this->q113_data."'")." 
                               ,'$this->q113_processado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Eventos da Importação do MEI por Responsável ($this->q113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Eventos da Importação do MEI por Responsável já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Eventos da Importação do MEI por Responsável ($this->q113_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q113_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16290,'$this->q113_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2854,16290,'','".AddSlashes(pg_result($resaco,0,'q113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2854,16291,'','".AddSlashes(pg_result($resaco,0,'q113_meiimportalinharesponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2854,16292,'','".AddSlashes(pg_result($resaco,0,'q113_meievento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2854,16293,'','".AddSlashes(pg_result($resaco,0,'q113_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2854,16294,'','".AddSlashes(pg_result($resaco,0,'q113_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q113_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimportalinharesponsavelmeievento set ";
     $virgula = "";
     if(trim($this->q113_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_sequencial"])){ 
       $sql  .= $virgula." q113_sequencial = $this->q113_sequencial ";
       $virgula = ",";
       if(trim($this->q113_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q113_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q113_meiimportalinharesponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_meiimportalinharesponsavel"])){ 
       $sql  .= $virgula." q113_meiimportalinharesponsavel = $this->q113_meiimportalinharesponsavel ";
       $virgula = ",";
       if(trim($this->q113_meiimportalinharesponsavel) == null ){ 
         $this->erro_sql = " Campo Importação do MEI por Resposável nao Informado.";
         $this->erro_campo = "q113_meiimportalinharesponsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q113_meievento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_meievento"])){ 
       $sql  .= $virgula." q113_meievento = $this->q113_meievento ";
       $virgula = ",";
       if(trim($this->q113_meievento) == null ){ 
         $this->erro_sql = " Campo Eventos do MEI nao Informado.";
         $this->erro_campo = "q113_meievento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q113_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q113_data_dia"] !="") ){ 
       $sql  .= $virgula." q113_data = '$this->q113_data' ";
       $virgula = ",";
       if(trim($this->q113_data) == null ){ 
         $this->erro_sql = " Campo Data do Evento nao Informado.";
         $this->erro_campo = "q113_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q113_data_dia"])){ 
         $sql  .= $virgula." q113_data = null ";
         $virgula = ",";
         if(trim($this->q113_data) == null ){ 
           $this->erro_sql = " Campo Data do Evento nao Informado.";
           $this->erro_campo = "q113_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q113_processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q113_processado"])){ 
       $sql  .= $virgula." q113_processado = '$this->q113_processado' ";
       $virgula = ",";
       if(trim($this->q113_processado) == null ){ 
         $this->erro_sql = " Campo Processado nao Informado.";
         $this->erro_campo = "q113_processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q113_sequencial!=null){
       $sql .= " q113_sequencial = $this->q113_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q113_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16290,'$this->q113_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_sequencial"]) || $this->q113_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2854,16290,'".AddSlashes(pg_result($resaco,$conresaco,'q113_sequencial'))."','$this->q113_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_meiimportalinharesponsavel"]) || $this->q113_meiimportalinharesponsavel != "")
           $resac = db_query("insert into db_acount values($acount,2854,16291,'".AddSlashes(pg_result($resaco,$conresaco,'q113_meiimportalinharesponsavel'))."','$this->q113_meiimportalinharesponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_meievento"]) || $this->q113_meievento != "")
           $resac = db_query("insert into db_acount values($acount,2854,16292,'".AddSlashes(pg_result($resaco,$conresaco,'q113_meievento'))."','$this->q113_meievento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_data"]) || $this->q113_data != "")
           $resac = db_query("insert into db_acount values($acount,2854,16293,'".AddSlashes(pg_result($resaco,$conresaco,'q113_data'))."','$this->q113_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q113_processado"]) || $this->q113_processado != "")
           $resac = db_query("insert into db_acount values($acount,2854,16294,'".AddSlashes(pg_result($resaco,$conresaco,'q113_processado'))."','$this->q113_processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos da Importação do MEI por Responsável nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Eventos da Importação do MEI por Responsável nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q113_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q113_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16290,'$q113_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2854,16290,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2854,16291,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_meiimportalinharesponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2854,16292,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_meievento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2854,16293,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2854,16294,'','".AddSlashes(pg_result($resaco,$iresaco,'q113_processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimportalinharesponsavelmeievento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q113_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q113_sequencial = $q113_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Eventos da Importação do MEI por Responsável nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q113_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Eventos da Importação do MEI por Responsável nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q113_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q113_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimportalinharesponsavelmeievento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportalinharesponsavelmeievento ";
     $sql .= "      inner join meievento  on  meievento.q101_sequencial = meiimportalinharesponsavelmeievento.q113_meievento";
     $sql .= "      inner join meiimportalinharesponsavel  on  meiimportalinharesponsavel.q108_sequencial = meiimportalinharesponsavelmeievento.q113_meiimportalinharesponsavel";
     $sql .= "      inner join meigrupoevento  on  meigrupoevento.q100_sequencial = meievento.q101_meigrupoevento";
     $sql .= "      inner join municipiosiafi  on  municipiosiafi.q110_sequencial = meiimportalinharesponsavel.q108_municsiafi";
     $sql .= "      inner join meiimportalinha  as a on   a.q105_sequencial = meiimportalinharesponsavel.q108_meiimportalinha";
     $sql2 = "";
     if($dbwhere==""){
       if($q113_sequencial!=null ){
         $sql2 .= " where meiimportalinharesponsavelmeievento.q113_sequencial = $q113_sequencial "; 
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
   function sql_query_file ( $q113_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimportalinharesponsavelmeievento ";
     $sql2 = "";
     if($dbwhere==""){
       if($q113_sequencial!=null ){
         $sql2 .= " where meiimportalinharesponsavelmeievento.q113_sequencial = $q113_sequencial "; 
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