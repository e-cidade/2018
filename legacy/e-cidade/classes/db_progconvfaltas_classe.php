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

//MODULO: educação
//CLASSE DA ENTIDADE progconvfaltas
class cl_progconvfaltas { 
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
   var $ed128_i_codigo = 0; 
   var $ed128_i_progconvres = 0; 
   var $ed128_c_abonada = null; 
   var $ed128_t_obs = null; 
   var $ed128_d_data_dia = null; 
   var $ed128_d_data_mes = null; 
   var $ed128_d_data_ano = null; 
   var $ed128_d_data = null; 
   var $ed128_c_numfono = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed128_i_codigo = int8 = Código 
                 ed128_i_progconvres = int8 = Convocação 
                 ed128_c_abonada = char(1) = Falta Abonada 
                 ed128_t_obs = text = Justificativa 
                 ed128_d_data = date = Data 
                 ed128_c_numfono = char(20) = N° do Fono 
                 ";
   //funcao construtor da classe 
   function cl_progconvfaltas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progconvfaltas"); 
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
       $this->ed128_i_codigo = ($this->ed128_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_i_codigo"]:$this->ed128_i_codigo);
       $this->ed128_i_progconvres = ($this->ed128_i_progconvres == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_i_progconvres"]:$this->ed128_i_progconvres);
       $this->ed128_c_abonada = ($this->ed128_c_abonada == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_c_abonada"]:$this->ed128_c_abonada);
       $this->ed128_t_obs = ($this->ed128_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_t_obs"]:$this->ed128_t_obs);
       if($this->ed128_d_data == ""){
         $this->ed128_d_data_dia = ($this->ed128_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_d_data_dia"]:$this->ed128_d_data_dia);
         $this->ed128_d_data_mes = ($this->ed128_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_d_data_mes"]:$this->ed128_d_data_mes);
         $this->ed128_d_data_ano = ($this->ed128_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_d_data_ano"]:$this->ed128_d_data_ano);
         if($this->ed128_d_data_dia != ""){
            $this->ed128_d_data = $this->ed128_d_data_ano."-".$this->ed128_d_data_mes."-".$this->ed128_d_data_dia;
         }
       }
       $this->ed128_c_numfono = ($this->ed128_c_numfono == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_c_numfono"]:$this->ed128_c_numfono);
     }else{
       $this->ed128_i_codigo = ($this->ed128_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed128_i_codigo"]:$this->ed128_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed128_i_codigo){ 
      $this->atualizacampos();
     if($this->ed128_i_progconvres == null ){ 
       $this->erro_sql = " Campo Convocação nao Informado.";
       $this->erro_campo = "ed128_i_progconvres";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed128_c_abonada == null ){ 
       $this->erro_sql = " Campo Falta Abonada nao Informado.";
       $this->erro_campo = "ed128_c_abonada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed128_d_data == null ){ 
       $this->ed128_d_data = "null";
     }
     if($ed128_i_codigo == "" || $ed128_i_codigo == null ){
       $result = db_query("select nextval('progconvfaltas_ed128_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progconvfaltas_ed128_i_codigo_seq do campo: ed128_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed128_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progconvfaltas_ed128_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed128_i_codigo)){
         $this->erro_sql = " Campo ed128_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed128_i_codigo = $ed128_i_codigo; 
       }
     }
     if(($this->ed128_i_codigo == null) || ($this->ed128_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed128_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progconvfaltas(
                                       ed128_i_codigo 
                                      ,ed128_i_progconvres 
                                      ,ed128_c_abonada 
                                      ,ed128_t_obs 
                                      ,ed128_d_data 
                                      ,ed128_c_numfono 
                       )
                values (
                                $this->ed128_i_codigo 
                               ,$this->ed128_i_progconvres 
                               ,'$this->ed128_c_abonada' 
                               ,'$this->ed128_t_obs' 
                               ,".($this->ed128_d_data == "null" || $this->ed128_d_data == ""?"null":"'".$this->ed128_d_data."'")." 
                               ,'$this->ed128_c_numfono' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registro das Faltas nas Convocações ($this->ed128_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro das Faltas nas Convocações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registro das Faltas nas Convocações ($this->ed128_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed128_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009207,'$this->ed128_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010188,1009207,'','".AddSlashes(pg_result($resaco,0,'ed128_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010188,1009208,'','".AddSlashes(pg_result($resaco,0,'ed128_i_progconvres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010188,1009212,'','".AddSlashes(pg_result($resaco,0,'ed128_c_abonada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010188,1009209,'','".AddSlashes(pg_result($resaco,0,'ed128_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010188,1009210,'','".AddSlashes(pg_result($resaco,0,'ed128_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010188,1009211,'','".AddSlashes(pg_result($resaco,0,'ed128_c_numfono'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed128_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update progconvfaltas set ";
     $virgula = "";
     if(trim($this->ed128_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_i_codigo"])){ 
       $sql  .= $virgula." ed128_i_codigo = $this->ed128_i_codigo ";
       $virgula = ",";
       if(trim($this->ed128_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed128_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_i_progconvres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_i_progconvres"])){ 
       $sql  .= $virgula." ed128_i_progconvres = $this->ed128_i_progconvres ";
       $virgula = ",";
       if(trim($this->ed128_i_progconvres) == null ){ 
         $this->erro_sql = " Campo Convocação nao Informado.";
         $this->erro_campo = "ed128_i_progconvres";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_c_abonada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_c_abonada"])){ 
       $sql  .= $virgula." ed128_c_abonada = '$this->ed128_c_abonada' ";
       $virgula = ",";
       if(trim($this->ed128_c_abonada) == null ){ 
         $this->erro_sql = " Campo Falta Abonada nao Informado.";
         $this->erro_campo = "ed128_c_abonada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed128_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_t_obs"])){ 
       $sql  .= $virgula." ed128_t_obs = '$this->ed128_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed128_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed128_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed128_d_data = '$this->ed128_d_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_d_data_dia"])){ 
         $sql  .= $virgula." ed128_d_data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed128_c_numfono)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed128_c_numfono"])){ 
       $sql  .= $virgula." ed128_c_numfono = '$this->ed128_c_numfono' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed128_i_codigo!=null){
       $sql .= " ed128_i_codigo = $this->ed128_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed128_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009207,'$this->ed128_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010188,1009207,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_i_codigo'))."','$this->ed128_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_i_progconvres"]))
           $resac = db_query("insert into db_acount values($acount,1010188,1009208,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_i_progconvres'))."','$this->ed128_i_progconvres',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_c_abonada"]))
           $resac = db_query("insert into db_acount values($acount,1010188,1009212,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_c_abonada'))."','$this->ed128_c_abonada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010188,1009209,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_t_obs'))."','$this->ed128_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010188,1009210,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_d_data'))."','$this->ed128_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed128_c_numfono"]))
           $resac = db_query("insert into db_acount values($acount,1010188,1009211,'".AddSlashes(pg_result($resaco,$conresaco,'ed128_c_numfono'))."','$this->ed128_c_numfono',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro das Faltas nas Convocações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro das Faltas nas Convocações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed128_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed128_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed128_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009207,'$ed128_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010188,1009207,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010188,1009208,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_i_progconvres'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010188,1009212,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_c_abonada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010188,1009209,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010188,1009210,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010188,1009211,'','".AddSlashes(pg_result($resaco,$iresaco,'ed128_c_numfono'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progconvfaltas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed128_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed128_i_codigo = $ed128_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registro das Faltas nas Convocações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed128_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registro das Faltas nas Convocações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed128_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed128_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:progconvfaltas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed128_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progconvfaltas ";
     $sql .= "      inner join progconvocacaores  on  progconvocacaores.ed127_i_codigo = progconvfaltas.ed128_i_progconvres";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = progconvocacaores.ed127_i_usuario";
     $sql .= "      inner join progmatricula  on  progmatricula.ed112_i_codigo = progconvocacaores.ed127_i_progmatricula";
     $sql2 = "";
     if($dbwhere==""){
       if($ed128_i_codigo!=null ){
         $sql2 .= " where progconvfaltas.ed128_i_codigo = $ed128_i_codigo "; 
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
   function sql_query_file ( $ed128_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progconvfaltas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed128_i_codigo!=null ){
         $sql2 .= " where progconvfaltas.ed128_i_codigo = $ed128_i_codigo "; 
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