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
//CLASSE DA ENTIDADE agendas
class cl_agendas { 
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
   var $ed17_i_codigo = 0; 
   var $ed17_i_matricula = 0; 
   var $ed17_i_laboratorio = 0; 
   var $ed17_d_data_dia = null; 
   var $ed17_d_data_mes = null; 
   var $ed17_d_data_ano = null; 
   var $ed17_d_data = null; 
   var $ed17_c_inicial = null; 
   var $ed17_c_final = null; 
   var $ed17_c_status = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed17_i_codigo = int8 = Código 
                 ed17_i_matricula = int8 = Matricula 
                 ed17_i_laboratorio = int8 = Laboratorio 
                 ed17_d_data = date = Data 
                 ed17_c_inicial = char(5) = Hora Inicial 
                 ed17_c_final = char(5) = Hora Final 
                 ed17_c_status = char(20) = Status 
                 ";
   //funcao construtor da classe 
   function cl_agendas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("agendas"); 
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
       $this->ed17_i_codigo = ($this->ed17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"]:$this->ed17_i_codigo);
       $this->ed17_i_matricula = ($this->ed17_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_matricula"]:$this->ed17_i_matricula);
       $this->ed17_i_laboratorio = ($this->ed17_i_laboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_laboratorio"]:$this->ed17_i_laboratorio);
       if($this->ed17_d_data == ""){
         $this->ed17_d_data_dia = ($this->ed17_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_d_data_dia"]:$this->ed17_d_data_dia);
         $this->ed17_d_data_mes = ($this->ed17_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_d_data_mes"]:$this->ed17_d_data_mes);
         $this->ed17_d_data_ano = ($this->ed17_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_d_data_ano"]:$this->ed17_d_data_ano);
         if($this->ed17_d_data_dia != ""){
            $this->ed17_d_data = $this->ed17_d_data_ano."-".$this->ed17_d_data_mes."-".$this->ed17_d_data_dia;
         }
       }
       $this->ed17_c_inicial = ($this->ed17_c_inicial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_c_inicial"]:$this->ed17_c_inicial);
       $this->ed17_c_final = ($this->ed17_c_final == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_c_final"]:$this->ed17_c_final);
       $this->ed17_c_status = ($this->ed17_c_status == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_c_status"]:$this->ed17_c_status);
     }else{
       $this->ed17_i_codigo = ($this->ed17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"]:$this->ed17_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed17_i_codigo){ 
      $this->atualizacampos();
     if($this->ed17_i_matricula == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "ed17_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_i_laboratorio == null ){ 
       $this->erro_sql = " Campo Laboratorio nao Informado.";
       $this->erro_campo = "ed17_i_laboratorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed17_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_c_inicial == null ){ 
       $this->erro_sql = " Campo Hora Inicial nao Informado.";
       $this->erro_campo = "ed17_c_inicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_c_final == null ){ 
       $this->erro_sql = " Campo Hora Final nao Informado.";
       $this->erro_campo = "ed17_c_final";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed17_c_status == null ){ 
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "ed17_c_status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed17_i_codigo == "" || $ed17_i_codigo == null ){
       $result = @pg_query("select nextval('agendas_ed17_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: agendas_ed17_i_codigo_seq do campo: ed17_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed17_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from agendas_ed17_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed17_i_codigo)){
         $this->erro_sql = " Campo ed17_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed17_i_codigo = $ed17_i_codigo; 
       }
     }
     if(($this->ed17_i_codigo == null) || ($this->ed17_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed17_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into agendas(
                                       ed17_i_codigo 
                                      ,ed17_i_matricula 
                                      ,ed17_i_laboratorio 
                                      ,ed17_d_data 
                                      ,ed17_c_inicial 
                                      ,ed17_c_final 
                                      ,ed17_c_status 
                       )
                values (
                                $this->ed17_i_codigo 
                               ,$this->ed17_i_matricula 
                               ,$this->ed17_i_laboratorio 
                               ,".($this->ed17_d_data == "null" || $this->ed17_d_data == ""?"null":"'".$this->ed17_d_data."'")." 
                               ,'$this->ed17_c_inicial' 
                               ,'$this->ed17_c_final' 
                               ,'$this->ed17_c_status' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Agenda ($this->ed17_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Agenda já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Agenda ($this->ed17_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed17_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006038,'$this->ed17_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006038,'','".AddSlashes(pg_result($resaco,0,'ed17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006055,'','".AddSlashes(pg_result($resaco,0,'ed17_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006057,'','".AddSlashes(pg_result($resaco,0,'ed17_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006039,'','".AddSlashes(pg_result($resaco,0,'ed17_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006036,'','".AddSlashes(pg_result($resaco,0,'ed17_c_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006037,'','".AddSlashes(pg_result($resaco,0,'ed17_c_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006009,1006040,'','".AddSlashes(pg_result($resaco,0,'ed17_c_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed17_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update agendas set ";
     $virgula = "";
     if(trim($this->ed17_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"])){ 
       $sql  .= $virgula." ed17_i_codigo = $this->ed17_i_codigo ";
       $virgula = ",";
       if(trim($this->ed17_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed17_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_matricula"])){ 
       $sql  .= $virgula." ed17_i_matricula = $this->ed17_i_matricula ";
       $virgula = ",";
       if(trim($this->ed17_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "ed17_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_i_laboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_laboratorio"])){ 
       $sql  .= $virgula." ed17_i_laboratorio = $this->ed17_i_laboratorio ";
       $virgula = ",";
       if(trim($this->ed17_i_laboratorio) == null ){ 
         $this->erro_sql = " Campo Laboratorio nao Informado.";
         $this->erro_campo = "ed17_i_laboratorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed17_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed17_d_data = '$this->ed17_d_data' ";
       $virgula = ",";
       if(trim($this->ed17_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed17_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_d_data_dia"])){ 
         $sql  .= $virgula." ed17_d_data = null ";
         $virgula = ",";
         if(trim($this->ed17_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed17_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed17_c_inicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_c_inicial"])){ 
       $sql  .= $virgula." ed17_c_inicial = '$this->ed17_c_inicial' ";
       $virgula = ",";
       if(trim($this->ed17_c_inicial) == null ){ 
         $this->erro_sql = " Campo Hora Inicial nao Informado.";
         $this->erro_campo = "ed17_c_inicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_c_final)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_c_final"])){ 
       $sql  .= $virgula." ed17_c_final = '$this->ed17_c_final' ";
       $virgula = ",";
       if(trim($this->ed17_c_final) == null ){ 
         $this->erro_sql = " Campo Hora Final nao Informado.";
         $this->erro_campo = "ed17_c_final";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed17_c_status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed17_c_status"])){ 
       $sql  .= $virgula." ed17_c_status = '$this->ed17_c_status' ";
       $virgula = ",";
       if(trim($this->ed17_c_status) == null ){ 
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "ed17_c_status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed17_i_codigo!=null){
       $sql .= " ed17_i_codigo = $this->ed17_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed17_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006038,'$this->ed17_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006038,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_codigo'))."','$this->ed17_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_matricula"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006055,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_matricula'))."','$this->ed17_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_i_laboratorio"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006057,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_i_laboratorio'))."','$this->ed17_i_laboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006039,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_d_data'))."','$this->ed17_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_c_inicial"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006036,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_c_inicial'))."','$this->ed17_c_inicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_c_final"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006037,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_c_final'))."','$this->ed17_c_final',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed17_c_status"]))
           $resac = pg_query("insert into db_acount values($acount,1006009,1006040,'".AddSlashes(pg_result($resaco,$conresaco,'ed17_c_status'))."','$this->ed17_c_status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed17_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed17_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006038,'$ed17_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006038,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006055,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006057,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_i_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006039,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006036,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_c_inicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006037,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_c_final'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006009,1006040,'','".AddSlashes(pg_result($resaco,$iresaco,'ed17_c_status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from agendas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed17_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed17_i_codigo = $ed17_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Agenda nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed17_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Agenda nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed17_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed17_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:agendas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendas ";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = agendas.ed17_i_matricula";
     $sql .= "      inner join laboratorios  on  laboratorios.ed16_i_codigo = agendas.ed17_i_laboratorio";
     $sql .= "      inner join series  on  series.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed17_i_codigo!=null ){
         $sql2 .= " where agendas.ed17_i_codigo = $ed17_i_codigo "; 
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
   function sql_query_file ( $ed17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from agendas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed17_i_codigo!=null ){
         $sql2 .= " where agendas.ed17_i_codigo = $ed17_i_codigo "; 
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