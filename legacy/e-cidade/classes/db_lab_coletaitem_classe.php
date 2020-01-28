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

//MODULO: Laborat�rio
//CLASSE DA ENTIDADE lab_coletaitem
class cl_lab_coletaitem { 
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
   var $la32_i_codigo = 0; 
   var $la32_i_usuario = 0; 
   var $la32_i_requiitem = 0; 
   var $la32_d_data_dia = null; 
   var $la32_d_data_mes = null; 
   var $la32_d_data_ano = null; 
   var $la32_d_data = null; 
   var $la32_c_hora = null; 
   var $la32_i_avisapaciente = 0; 
   var $la32_c_horaentrega = null; 
   var $la32_d_entrega_dia = null; 
   var $la32_d_entrega_mes = null; 
   var $la32_d_entrega_ano = null; 
   var $la32_d_entrega = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la32_i_codigo = int4 = Código 
                 la32_i_usuario = int4 = Usuário 
                 la32_i_requiitem = int4 = Requisição 
                 la32_d_data = date = Data 
                 la32_c_hora = char(5) = Hora 
                 la32_i_avisapaciente = int4 = Avisar Paciente 
                 la32_c_horaentrega = char(5) = Hora 
                 la32_d_entrega = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_lab_coletaitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_coletaitem"); 
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
       $this->la32_i_codigo = ($this->la32_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_i_codigo"]:$this->la32_i_codigo);
       $this->la32_i_usuario = ($this->la32_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_i_usuario"]:$this->la32_i_usuario);
       $this->la32_i_requiitem = ($this->la32_i_requiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_i_requiitem"]:$this->la32_i_requiitem);
       if($this->la32_d_data == ""){
         $this->la32_d_data_dia = ($this->la32_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_d_data_dia"]:$this->la32_d_data_dia);
         $this->la32_d_data_mes = ($this->la32_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_d_data_mes"]:$this->la32_d_data_mes);
         $this->la32_d_data_ano = ($this->la32_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_d_data_ano"]:$this->la32_d_data_ano);
         if($this->la32_d_data_dia != ""){
            $this->la32_d_data = $this->la32_d_data_ano."-".$this->la32_d_data_mes."-".$this->la32_d_data_dia;
         }
       }
       $this->la32_c_hora = ($this->la32_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_c_hora"]:$this->la32_c_hora);
       $this->la32_i_avisapaciente = ($this->la32_i_avisapaciente == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_i_avisapaciente"]:$this->la32_i_avisapaciente);
       $this->la32_c_horaentrega = ($this->la32_c_horaentrega == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_c_horaentrega"]:$this->la32_c_horaentrega);
       if($this->la32_d_entrega == ""){
         $this->la32_d_entrega_dia = ($this->la32_d_entrega_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_d_entrega_dia"]:$this->la32_d_entrega_dia);
         $this->la32_d_entrega_mes = ($this->la32_d_entrega_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_d_entrega_mes"]:$this->la32_d_entrega_mes);
         $this->la32_d_entrega_ano = ($this->la32_d_entrega_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_d_entrega_ano"]:$this->la32_d_entrega_ano);
         if($this->la32_d_entrega_dia != ""){
            $this->la32_d_entrega = $this->la32_d_entrega_ano."-".$this->la32_d_entrega_mes."-".$this->la32_d_entrega_dia;
         }
       }
     }else{
       $this->la32_i_codigo = ($this->la32_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la32_i_codigo"]:$this->la32_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la32_i_codigo){ 
      $this->atualizacampos();
     if($this->la32_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "la32_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la32_i_requiitem == null ){ 
       $this->erro_sql = " Campo Requisiçao nao Informado.";
       $this->erro_campo = "la32_i_requiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la32_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "la32_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la32_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "la32_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la32_i_avisapaciente == null ){ 
       $this->erro_sql = " Campo Avisar Paciente nao Informado.";
       $this->erro_campo = "la32_i_avisapaciente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la32_d_entrega == null ){ 
       $this->la32_d_entrega = "null";
     }
     if($la32_i_codigo == "" || $la32_i_codigo == null ){
       $result = db_query("select nextval('lab_coletaitem_la32_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_coletaitem_la32_i_codigo_seq do campo: la32_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la32_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_coletaitem_la32_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la32_i_codigo)){
         $this->erro_sql = " Campo la32_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la32_i_codigo = $la32_i_codigo; 
       }
     }
     if(($this->la32_i_codigo == null) || ($this->la32_i_codigo == "") ){ 
       $this->erro_sql = " Campo la32_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_coletaitem(
                                       la32_i_codigo 
                                      ,la32_i_usuario 
                                      ,la32_i_requiitem 
                                      ,la32_d_data 
                                      ,la32_c_hora 
                                      ,la32_i_avisapaciente 
                                      ,la32_c_horaentrega 
                                      ,la32_d_entrega 
                       )
                values (
                                $this->la32_i_codigo 
                               ,$this->la32_i_usuario 
                               ,$this->la32_i_requiitem 
                               ,".($this->la32_d_data == "null" || $this->la32_d_data == ""?"null":"'".$this->la32_d_data."'")." 
                               ,'$this->la32_c_hora' 
                               ,$this->la32_i_avisapaciente 
                               ,'$this->la32_c_horaentrega' 
                               ,".($this->la32_d_entrega == "null" || $this->la32_d_entrega == ""?"null":"'".$this->la32_d_entrega."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Coleta do Item ($this->la32_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Coleta do Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Coleta do Item ($this->la32_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la32_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la32_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16536,'$this->la32_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2893,16536,'','".AddSlashes(pg_result($resaco,0,'la32_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16537,'','".AddSlashes(pg_result($resaco,0,'la32_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16538,'','".AddSlashes(pg_result($resaco,0,'la32_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16539,'','".AddSlashes(pg_result($resaco,0,'la32_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16540,'','".AddSlashes(pg_result($resaco,0,'la32_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16541,'','".AddSlashes(pg_result($resaco,0,'la32_i_avisapaciente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16572,'','".AddSlashes(pg_result($resaco,0,'la32_c_horaentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2893,16573,'','".AddSlashes(pg_result($resaco,0,'la32_d_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la32_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_coletaitem set ";
     $virgula = "";
     if(trim($this->la32_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_i_codigo"])){ 
       $sql  .= $virgula." la32_i_codigo = $this->la32_i_codigo ";
       $virgula = ",";
       if(trim($this->la32_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la32_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la32_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_i_usuario"])){ 
       $sql  .= $virgula." la32_i_usuario = $this->la32_i_usuario ";
       $virgula = ",";
       if(trim($this->la32_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "la32_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la32_i_requiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_i_requiitem"])){ 
       $sql  .= $virgula." la32_i_requiitem = $this->la32_i_requiitem ";
       $virgula = ",";
       if(trim($this->la32_i_requiitem) == null ){ 
         $this->erro_sql = " Campo Requisição nao Informado.";
         $this->erro_campo = "la32_i_requiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la32_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la32_d_data_dia"] !="") ){ 
       $sql  .= $virgula." la32_d_data = '$this->la32_d_data' ";
       $virgula = ",";
       if(trim($this->la32_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "la32_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la32_d_data_dia"])){ 
         $sql  .= $virgula." la32_d_data = null ";
         $virgula = ",";
         if(trim($this->la32_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "la32_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la32_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_c_hora"])){ 
       $sql  .= $virgula." la32_c_hora = '$this->la32_c_hora' ";
       $virgula = ",";
       if(trim($this->la32_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "la32_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la32_i_avisapaciente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_i_avisapaciente"])){ 
       $sql  .= $virgula." la32_i_avisapaciente = $this->la32_i_avisapaciente ";
       $virgula = ",";
       if(trim($this->la32_i_avisapaciente) == null ){ 
         $this->erro_sql = " Campo Avisar Paciente nao Informado.";
         $this->erro_campo = "la32_i_avisapaciente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la32_c_horaentrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_c_horaentrega"])){ 
       $sql  .= $virgula." la32_c_horaentrega = '$this->la32_c_horaentrega' ";
       $virgula = ",";
     }
     if(trim($this->la32_d_entrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la32_d_entrega_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la32_d_entrega_dia"] !="") ){ 
       $sql  .= $virgula." la32_d_entrega = '$this->la32_d_entrega' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la32_d_entrega_dia"])){ 
         $sql  .= $virgula." la32_d_entrega = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($la32_i_codigo!=null){
       $sql .= " la32_i_codigo = $this->la32_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la32_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16536,'$this->la32_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_i_codigo"]) || $this->la32_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2893,16536,'".AddSlashes(pg_result($resaco,$conresaco,'la32_i_codigo'))."','$this->la32_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_i_usuario"]) || $this->la32_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2893,16537,'".AddSlashes(pg_result($resaco,$conresaco,'la32_i_usuario'))."','$this->la32_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_i_requiitem"]) || $this->la32_i_requiitem != "")
           $resac = db_query("insert into db_acount values($acount,2893,16538,'".AddSlashes(pg_result($resaco,$conresaco,'la32_i_requiitem'))."','$this->la32_i_requiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_d_data"]) || $this->la32_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2893,16539,'".AddSlashes(pg_result($resaco,$conresaco,'la32_d_data'))."','$this->la32_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_c_hora"]) || $this->la32_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2893,16540,'".AddSlashes(pg_result($resaco,$conresaco,'la32_c_hora'))."','$this->la32_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_i_avisapaciente"]) || $this->la32_i_avisapaciente != "")
           $resac = db_query("insert into db_acount values($acount,2893,16541,'".AddSlashes(pg_result($resaco,$conresaco,'la32_i_avisapaciente'))."','$this->la32_i_avisapaciente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_c_horaentrega"]) || $this->la32_c_horaentrega != "")
           $resac = db_query("insert into db_acount values($acount,2893,16572,'".AddSlashes(pg_result($resaco,$conresaco,'la32_c_horaentrega'))."','$this->la32_c_horaentrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la32_d_entrega"]) || $this->la32_d_entrega != "")
           $resac = db_query("insert into db_acount values($acount,2893,16573,'".AddSlashes(pg_result($resaco,$conresaco,'la32_d_entrega'))."','$this->la32_d_entrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Coleta do Item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la32_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Coleta do Item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la32_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la32_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16536,'$la32_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2893,16536,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16537,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16538,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16539,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16540,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16541,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_i_avisapaciente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16572,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_c_horaentrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2893,16573,'','".AddSlashes(pg_result($resaco,$iresaco,'la32_d_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_coletaitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la32_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la32_i_codigo = $la32_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Coleta do Item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la32_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Coleta do Item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la32_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la32_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_coletaitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la32_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_coletaitem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_coletaitem.la32_i_usuario";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = lab_coletaitem.la32_i_requiitem";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql2 = "";
     if($dbwhere==""){
       if($la32_i_codigo!=null ){
         $sql2 .= " where lab_coletaitem.la32_i_codigo = $la32_i_codigo "; 
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
   function sql_query_file ( $la32_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_coletaitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($la32_i_codigo!=null ){
         $sql2 .= " where lab_coletaitem.la32_i_codigo = $la32_i_codigo "; 
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