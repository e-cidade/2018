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
//CLASSE DA ENTIDADE pareceres
class cl_pareceres { 
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
   var $ed25_i_codigo = 0; 
   var $ed25_i_matricula = 0; 
   var $ed25_d_data_dia = null; 
   var $ed25_d_data_mes = null; 
   var $ed25_d_data_ano = null; 
   var $ed25_d_data = null; 
   var $ed25_t_atividades = null; 
   var $ed25_l_apto = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed25_i_codigo = int8 = Código 
                 ed25_i_matricula = int8 = Matrículas 
                 ed25_d_data = date = Data 
                 ed25_t_atividades = text = Atividades 
                 ed25_l_apto = bool = Apto 
                 ";
   //funcao construtor da classe 
   function cl_pareceres() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pareceres"); 
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
       $this->ed25_i_codigo = ($this->ed25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_i_codigo"]:$this->ed25_i_codigo);
       $this->ed25_i_matricula = ($this->ed25_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_i_matricula"]:$this->ed25_i_matricula);
       if($this->ed25_d_data == ""){
         $this->ed25_d_data_dia = ($this->ed25_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_d_data_dia"]:$this->ed25_d_data_dia);
         $this->ed25_d_data_mes = ($this->ed25_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_d_data_mes"]:$this->ed25_d_data_mes);
         $this->ed25_d_data_ano = ($this->ed25_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_d_data_ano"]:$this->ed25_d_data_ano);
         if($this->ed25_d_data_dia != ""){
            $this->ed25_d_data = $this->ed25_d_data_ano."-".$this->ed25_d_data_mes."-".$this->ed25_d_data_dia;
         }
       }
       $this->ed25_t_atividades = ($this->ed25_t_atividades == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_t_atividades"]:$this->ed25_t_atividades);
       $this->ed25_l_apto = ($this->ed25_l_apto == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed25_l_apto"]:$this->ed25_l_apto);
     }else{
       $this->ed25_i_codigo = ($this->ed25_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed25_i_codigo"]:$this->ed25_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed25_i_codigo){ 
      $this->atualizacampos();
     if($this->ed25_i_matricula == null ){ 
       $this->erro_sql = " Campo Matrículas nao Informado.";
       $this->erro_campo = "ed25_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed25_d_data == null ){
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed25_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed25_t_atividades == null ){ 
       $this->erro_sql = " Campo Atividades nao Informado.";
       $this->erro_campo = "ed25_t_atividades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed25_l_apto == null ){ 
       $this->erro_sql = " Campo Apto nao Informado.";
       $this->erro_campo = "ed25_l_apto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed25_i_codigo == "" || $ed25_i_codigo == null ){
       $result = @pg_query("select nextval('pareceres_ed25_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pareceres_ed25_i_codigo_seq do campo: ed25_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed25_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from pareceres_ed25_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed25_i_codigo)){
         $this->erro_sql = " Campo ed25_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed25_i_codigo = $ed25_i_codigo; 
       }
     }
     if(($this->ed25_i_codigo == null) || ($this->ed25_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed25_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pareceres(
                                       ed25_i_codigo 
                                      ,ed25_i_matricula 
                                      ,ed25_d_data 
                                      ,ed25_t_atividades 
                                      ,ed25_l_apto 
                       )
                values (
                                $this->ed25_i_codigo 
                               ,$this->ed25_i_matricula 
                               ,".($this->ed25_d_data == "null" || $this->ed25_d_data == ""?"null":"'".$this->ed25_d_data."'")." 
                               ,'$this->ed25_t_atividades' 
                               ,'$this->ed25_l_apto' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pareceres ($this->ed25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pareceres já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pareceres ($this->ed25_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed25_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed25_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006172,'$this->ed25_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1000030,1006172,'','".AddSlashes(pg_result($resaco,0,'ed25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000030,1006173,'','".AddSlashes(pg_result($resaco,0,'ed25_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000030,1006205,'','".AddSlashes(pg_result($resaco,0,'ed25_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000030,1006174,'','".AddSlashes(pg_result($resaco,0,'ed25_t_atividades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1000030,1006175,'','".AddSlashes(pg_result($resaco,0,'ed25_l_apto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed25_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update pareceres set ";
     $virgula = "";
     if(trim($this->ed25_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed25_i_codigo"])){ 
       $sql  .= $virgula." ed25_i_codigo = $this->ed25_i_codigo ";
       $virgula = ",";
       if(trim($this->ed25_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed25_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed25_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed25_i_matricula"])){ 
       $sql  .= $virgula." ed25_i_matricula = $this->ed25_i_matricula ";
       $virgula = ",";
       if(trim($this->ed25_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matrículas nao Informado.";
         $this->erro_campo = "ed25_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed25_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed25_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed25_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed25_d_data = '$this->ed25_d_data' ";
       $virgula = ",";
       if(trim($this->ed25_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed25_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed25_d_data_dia"])){ 
         $sql  .= $virgula." ed25_d_data = null ";
         $virgula = ",";
         if(trim($this->ed25_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed25_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed25_t_atividades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed25_t_atividades"])){ 
       $sql  .= $virgula." ed25_t_atividades = '$this->ed25_t_atividades' ";
       $virgula = ",";
       if(trim($this->ed25_t_atividades) == null ){ 
         $this->erro_sql = " Campo Atividades nao Informado.";
         $this->erro_campo = "ed25_t_atividades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed25_l_apto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed25_l_apto"])){ 
       $sql  .= $virgula." ed25_l_apto = '$this->ed25_l_apto' ";
       $virgula = ",";
       if(trim($this->ed25_l_apto) == null ){ 
         $this->erro_sql = " Campo Apto nao Informado.";
         $this->erro_campo = "ed25_l_apto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed25_i_codigo!=null){
       $sql .= " ed25_i_codigo = $this->ed25_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed25_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006172,'$this->ed25_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed25_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1000030,1006172,'".AddSlashes(pg_result($resaco,$conresaco,'ed25_i_codigo'))."','$this->ed25_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed25_i_matricula"]))
           $resac = pg_query("insert into db_acount values($acount,1000030,1006173,'".AddSlashes(pg_result($resaco,$conresaco,'ed25_i_matricula'))."','$this->ed25_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed25_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,1000030,1006205,'".AddSlashes(pg_result($resaco,$conresaco,'ed25_d_data'))."','$this->ed25_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed25_t_atividades"]))
           $resac = pg_query("insert into db_acount values($acount,1000030,1006174,'".AddSlashes(pg_result($resaco,$conresaco,'ed25_t_atividades'))."','$this->ed25_t_atividades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed25_l_apto"]))
           $resac = pg_query("insert into db_acount values($acount,1000030,1006175,'".AddSlashes(pg_result($resaco,$conresaco,'ed25_l_apto'))."','$this->ed25_l_apto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pareceres nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pareceres nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed25_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed25_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006172,'$ed25_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1000030,1006172,'','".AddSlashes(pg_result($resaco,$iresaco,'ed25_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000030,1006173,'','".AddSlashes(pg_result($resaco,$iresaco,'ed25_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000030,1006205,'','".AddSlashes(pg_result($resaco,$iresaco,'ed25_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000030,1006174,'','".AddSlashes(pg_result($resaco,$iresaco,'ed25_t_atividades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1000030,1006175,'','".AddSlashes(pg_result($resaco,$iresaco,'ed25_l_apto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pareceres
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed25_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed25_i_codigo = $ed25_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pareceres nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed25_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pareceres nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed25_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed25_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:pareceres";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pareceres ";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = pareceres.ed25_i_matricula";
     $sql .= "      inner join series  on  series.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed25_i_codigo!=null ){
         $sql2 .= " where pareceres.ed25_i_codigo = $ed25_i_codigo "; 
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
   function sql_query_file ( $ed25_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pareceres ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed25_i_codigo!=null ){
         $sql2 .= " where pareceres.ed25_i_codigo = $ed25_i_codigo "; 
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