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

//MODULO: material
//CLASSE DA ENTIDADE matanulitem
class cl_matanulitem { 
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
   var $m103_codigo = 0; 
   var $m103_id_usuario = 0; 
   var $m103_data_dia = null; 
   var $m103_data_mes = null; 
   var $m103_data_ano = null; 
   var $m103_data = null; 
   var $m103_hora = null; 
   var $m103_motivo = null; 
   var $m103_quantanulada = 0; 
   var $m103_tipoanu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m103_codigo = int8 = Código 
                 m103_id_usuario = int8 = Usuário 
                 m103_data = date = Data 
                 m103_hora = char(10) = Hora 
                 m103_motivo = text = Motivo 
                 m103_quantanulada = float4 = Quantidade Anulada 
                 m103_tipoanu = int8 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_matanulitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matanulitem"); 
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
       $this->m103_codigo = ($this->m103_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_codigo"]:$this->m103_codigo);
       $this->m103_id_usuario = ($this->m103_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_id_usuario"]:$this->m103_id_usuario);
       if($this->m103_data == ""){
         $this->m103_data_dia = ($this->m103_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_data_dia"]:$this->m103_data_dia);
         $this->m103_data_mes = ($this->m103_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_data_mes"]:$this->m103_data_mes);
         $this->m103_data_ano = ($this->m103_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_data_ano"]:$this->m103_data_ano);
         if($this->m103_data_dia != ""){
            $this->m103_data = $this->m103_data_ano."-".$this->m103_data_mes."-".$this->m103_data_dia;
         }
       }
       $this->m103_hora = ($this->m103_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_hora"]:$this->m103_hora);
       $this->m103_motivo = ($this->m103_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_motivo"]:$this->m103_motivo);
       $this->m103_quantanulada = ($this->m103_quantanulada == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_quantanulada"]:$this->m103_quantanulada);
       $this->m103_tipoanu = ($this->m103_tipoanu == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_tipoanu"]:$this->m103_tipoanu);
     }else{
       $this->m103_codigo = ($this->m103_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m103_codigo"]:$this->m103_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m103_codigo){ 
      $this->atualizacampos();
     if($this->m103_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "m103_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m103_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "m103_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m103_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "m103_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m103_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "m103_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m103_quantanulada == null ){ 
       $this->erro_sql = " Campo Quantidade Anulada nao Informado.";
       $this->erro_campo = "m103_quantanulada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m103_tipoanu == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "m103_tipoanu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m103_codigo == "" || $m103_codigo == null ){
       $result = db_query("select nextval('matanulitem_m103_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matanulitem_m103_codigo_seq do campo: m103_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m103_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matanulitem_m103_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m103_codigo)){
         $this->erro_sql = " Campo m103_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m103_codigo = $m103_codigo; 
       }
     }
     if(($this->m103_codigo == null) || ($this->m103_codigo == "") ){ 
       $this->erro_sql = " Campo m103_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matanulitem(
                                       m103_codigo 
                                      ,m103_id_usuario 
                                      ,m103_data 
                                      ,m103_hora 
                                      ,m103_motivo 
                                      ,m103_quantanulada 
                                      ,m103_tipoanu 
                       )
                values (
                                $this->m103_codigo 
                               ,$this->m103_id_usuario 
                               ,".($this->m103_data == "null" || $this->m103_data == ""?"null":"'".$this->m103_data."'")." 
                               ,'$this->m103_hora' 
                               ,'$this->m103_motivo' 
                               ,$this->m103_quantanulada 
                               ,$this->m103_tipoanu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matanulitem ($this->m103_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matanulitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matanulitem ($this->m103_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m103_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m103_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15257,'$this->m103_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2690,15257,'','".AddSlashes(pg_result($resaco,0,'m103_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2690,15258,'','".AddSlashes(pg_result($resaco,0,'m103_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2690,15259,'','".AddSlashes(pg_result($resaco,0,'m103_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2690,15260,'','".AddSlashes(pg_result($resaco,0,'m103_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2690,15261,'','".AddSlashes(pg_result($resaco,0,'m103_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2690,15262,'','".AddSlashes(pg_result($resaco,0,'m103_quantanulada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2690,15263,'','".AddSlashes(pg_result($resaco,0,'m103_tipoanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m103_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matanulitem set ";
     $virgula = "";
     if(trim($this->m103_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_codigo"])){ 
       $sql  .= $virgula." m103_codigo = $this->m103_codigo ";
       $virgula = ",";
       if(trim($this->m103_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m103_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m103_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_id_usuario"])){ 
       $sql  .= $virgula." m103_id_usuario = $this->m103_id_usuario ";
       $virgula = ",";
       if(trim($this->m103_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "m103_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m103_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m103_data_dia"] !="") ){ 
       $sql  .= $virgula." m103_data = '$this->m103_data' ";
       $virgula = ",";
       if(trim($this->m103_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "m103_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m103_data_dia"])){ 
         $sql  .= $virgula." m103_data = null ";
         $virgula = ",";
         if(trim($this->m103_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "m103_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m103_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_hora"])){ 
       $sql  .= $virgula." m103_hora = '$this->m103_hora' ";
       $virgula = ",";
       if(trim($this->m103_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "m103_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m103_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_motivo"])){ 
       $sql  .= $virgula." m103_motivo = '$this->m103_motivo' ";
       $virgula = ",";
       if(trim($this->m103_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "m103_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m103_quantanulada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_quantanulada"])){ 
       $sql  .= $virgula." m103_quantanulada = $this->m103_quantanulada ";
       $virgula = ",";
       if(trim($this->m103_quantanulada) == null ){ 
         $this->erro_sql = " Campo Quantidade Anulada nao Informado.";
         $this->erro_campo = "m103_quantanulada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m103_tipoanu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m103_tipoanu"])){ 
       $sql  .= $virgula." m103_tipoanu = $this->m103_tipoanu ";
       $virgula = ",";
       if(trim($this->m103_tipoanu) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "m103_tipoanu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m103_codigo!=null){
       $sql .= " m103_codigo = $this->m103_codigo";
     }     
     $resaco = $this->sql_record($this->sql_query_file($this->m103_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15257,'$this->m103_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_codigo"]) || $this->m103_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2690,15257,'".AddSlashes(pg_result($resaco,$conresaco,'m103_codigo'))."','$this->m103_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_id_usuario"]) || $this->m103_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2690,15258,'".AddSlashes(pg_result($resaco,$conresaco,'m103_id_usuario'))."','$this->m103_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_data"]) || $this->m103_data != "")
           $resac = db_query("insert into db_acount values($acount,2690,15259,'".AddSlashes(pg_result($resaco,$conresaco,'m103_data'))."','$this->m103_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_hora"]) || $this->m103_hora != "")
           $resac = db_query("insert into db_acount values($acount,2690,15260,'".AddSlashes(pg_result($resaco,$conresaco,'m103_hora'))."','$this->m103_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_motivo"]) || $this->m103_motivo != "")
           $resac = db_query("insert into db_acount values($acount,2690,15261,'".AddSlashes(pg_result($resaco,$conresaco,'m103_motivo'))."','$this->m103_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_quantanulada"]) || $this->m103_quantanulada != "")
           $resac = db_query("insert into db_acount values($acount,2690,15262,'".AddSlashes(pg_result($resaco,$conresaco,'m103_quantanulada'))."','$this->m103_quantanulada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m103_tipoanu"]) || $this->m103_tipoanu != "")
           $resac = db_query("insert into db_acount values($acount,2690,15263,'".AddSlashes(pg_result($resaco,$conresaco,'m103_tipoanu'))."','$this->m103_tipoanu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matanulitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m103_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matanulitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m103_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m103_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m103_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m103_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15257,'$m103_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2690,15257,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2690,15258,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2690,15259,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2690,15260,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2690,15261,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2690,15262,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_quantanulada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2690,15263,'','".AddSlashes(pg_result($resaco,$iresaco,'m103_tipoanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matanulitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m103_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m103_codigo = $m103_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matanulitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m103_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matanulitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m103_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m103_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matanulitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m103_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matanulitem ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matanulitem.m103_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($m103_codigo!=null ){
         $sql2 .= " where matanulitem.m103_codigo = $m103_codigo "; 
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
   function sql_query_file ( $m103_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matanulitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m103_codigo!=null ){
         $sql2 .= " where matanulitem.m103_codigo = $m103_codigo "; 
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