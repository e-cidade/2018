<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_emissao
class cl_lab_emissao { 
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
   var $la34_i_codigo = 0; 
   var $la34_i_forma = 0; 
   var $la34_i_usuario = 0; 
   var $la34_i_requiitem = 0; 
   var $la34_d_data_dia = null; 
   var $la34_d_data_mes = null; 
   var $la34_d_data_ano = null; 
   var $la34_d_data = null; 
   var $la34_c_hora = null; 
   var $la34_o_laudo = 0; 
   var $la34_c_nomearq = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la34_i_codigo = int4 = Código 
                 la34_i_forma = int4 = Forma 
                 la34_i_usuario = int4 = Usuário 
                 la34_i_requiitem = int4 = Requisição 
                 la34_d_data = date = Data 
                 la34_c_hora = char(5) = Hora 
                 la34_o_laudo = oid = Laudo 
                 la34_c_nomearq = char(50) = Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_lab_emissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_emissao"); 
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
       $this->la34_i_codigo = ($this->la34_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_i_codigo"]:$this->la34_i_codigo);
       $this->la34_i_forma = ($this->la34_i_forma == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_i_forma"]:$this->la34_i_forma);
       $this->la34_i_usuario = ($this->la34_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_i_usuario"]:$this->la34_i_usuario);
       $this->la34_i_requiitem = ($this->la34_i_requiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_i_requiitem"]:$this->la34_i_requiitem);
       if($this->la34_d_data == ""){
         $this->la34_d_data_dia = ($this->la34_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_d_data_dia"]:$this->la34_d_data_dia);
         $this->la34_d_data_mes = ($this->la34_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_d_data_mes"]:$this->la34_d_data_mes);
         $this->la34_d_data_ano = ($this->la34_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_d_data_ano"]:$this->la34_d_data_ano);
         if($this->la34_d_data_dia != ""){
            $this->la34_d_data = $this->la34_d_data_ano."-".$this->la34_d_data_mes."-".$this->la34_d_data_dia;
         }
       }
       $this->la34_c_hora = ($this->la34_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_c_hora"]:$this->la34_c_hora);
       $this->la34_o_laudo = ($this->la34_o_laudo == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_o_laudo"]:$this->la34_o_laudo);
       $this->la34_c_nomearq = ($this->la34_c_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_c_nomearq"]:$this->la34_c_nomearq);
     }else{
       $this->la34_i_codigo = ($this->la34_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la34_i_codigo"]:$this->la34_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la34_i_codigo){ 
      $this->atualizacampos();
     if($this->la34_i_forma == null ){ 
       $this->erro_sql = " Campo Forma nao Informado.";
       $this->erro_campo = "la34_i_forma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la34_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "la34_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la34_i_requiitem == null ){ 
       $this->erro_sql = " Campo Requisição nao Informado.";
       $this->erro_campo = "la34_i_requiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la34_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "la34_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la34_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "la34_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la34_o_laudo == null ){ 
       $this->erro_sql = " Campo Laudo nao Informado.";
       $this->erro_campo = "la34_o_laudo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la34_c_nomearq == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "la34_c_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la34_i_codigo == "" || $la34_i_codigo == null ){
       $result = db_query("select nextval('lab_emissao_la34_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_emissao_la34_i_codigo_seq do campo: la34_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la34_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_emissao_la34_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la34_i_codigo)){
         $this->erro_sql = " Campo la34_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la34_i_codigo = $la34_i_codigo; 
       }
     }
     if(($this->la34_i_codigo == null) || ($this->la34_i_codigo == "") ){ 
       $this->erro_sql = " Campo la34_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_emissao(
                                       la34_i_codigo 
                                      ,la34_i_forma 
                                      ,la34_i_usuario 
                                      ,la34_i_requiitem 
                                      ,la34_d_data 
                                      ,la34_c_hora 
                                      ,la34_o_laudo 
                                      ,la34_c_nomearq 
                       )
                values (
                                $this->la34_i_codigo 
                               ,$this->la34_i_forma 
                               ,$this->la34_i_usuario 
                               ,$this->la34_i_requiitem 
                               ,".($this->la34_d_data == "null" || $this->la34_d_data == ""?"null":"'".$this->la34_d_data."'")." 
                               ,'$this->la34_c_hora' 
                               ,$this->la34_o_laudo 
                               ,'$this->la34_c_nomearq' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Emissão ($this->la34_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Emissão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Emissão ($this->la34_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la34_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la34_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16519,'$this->la34_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2894,16519,'','".AddSlashes(pg_result($resaco,0,'la34_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16521,'','".AddSlashes(pg_result($resaco,0,'la34_i_forma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16522,'','".AddSlashes(pg_result($resaco,0,'la34_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16523,'','".AddSlashes(pg_result($resaco,0,'la34_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16524,'','".AddSlashes(pg_result($resaco,0,'la34_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16525,'','".AddSlashes(pg_result($resaco,0,'la34_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16526,'','".AddSlashes(pg_result($resaco,0,'la34_o_laudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2894,16527,'','".AddSlashes(pg_result($resaco,0,'la34_c_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la34_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_emissao set ";
     $virgula = "";
     if(trim($this->la34_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_i_codigo"])){ 
       $sql  .= $virgula." la34_i_codigo = $this->la34_i_codigo ";
       $virgula = ",";
       if(trim($this->la34_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la34_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la34_i_forma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_i_forma"])){ 
       $sql  .= $virgula." la34_i_forma = $this->la34_i_forma ";
       $virgula = ",";
       if(trim($this->la34_i_forma) == null ){ 
         $this->erro_sql = " Campo Forma nao Informado.";
         $this->erro_campo = "la34_i_forma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la34_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_i_usuario"])){ 
       $sql  .= $virgula." la34_i_usuario = $this->la34_i_usuario ";
       $virgula = ",";
       if(trim($this->la34_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "la34_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la34_i_requiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_i_requiitem"])){ 
       $sql  .= $virgula." la34_i_requiitem = $this->la34_i_requiitem ";
       $virgula = ",";
       if(trim($this->la34_i_requiitem) == null ){ 
         $this->erro_sql = " Campo Requisição nao Informado.";
         $this->erro_campo = "la34_i_requiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la34_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["la34_d_data_dia"] !="") ){ 
       $sql  .= $virgula." la34_d_data = '$this->la34_d_data' ";
       $virgula = ",";
       if(trim($this->la34_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "la34_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["la34_d_data_dia"])){ 
         $sql  .= $virgula." la34_d_data = null ";
         $virgula = ",";
         if(trim($this->la34_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "la34_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->la34_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_c_hora"])){ 
       $sql  .= $virgula." la34_c_hora = '$this->la34_c_hora' ";
       $virgula = ",";
       if(trim($this->la34_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "la34_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la34_o_laudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_o_laudo"])){ 
       $sql  .= $virgula." la34_o_laudo = $this->la34_o_laudo ";
       $virgula = ",";
       if(trim($this->la34_o_laudo) == null ){ 
         $this->erro_sql = " Campo Laudo nao Informado.";
         $this->erro_campo = "la34_o_laudo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la34_c_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la34_c_nomearq"])){ 
       $sql  .= $virgula." la34_c_nomearq = '$this->la34_c_nomearq' ";
       $virgula = ",";
       if(trim($this->la34_c_nomearq) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "la34_c_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la34_i_codigo!=null){
       $sql .= " la34_i_codigo = $this->la34_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la34_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16519,'$this->la34_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_i_codigo"]) || $this->la34_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2894,16519,'".AddSlashes(pg_result($resaco,$conresaco,'la34_i_codigo'))."','$this->la34_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_i_forma"]) || $this->la34_i_forma != "")
           $resac = db_query("insert into db_acount values($acount,2894,16521,'".AddSlashes(pg_result($resaco,$conresaco,'la34_i_forma'))."','$this->la34_i_forma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_i_usuario"]) || $this->la34_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2894,16522,'".AddSlashes(pg_result($resaco,$conresaco,'la34_i_usuario'))."','$this->la34_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_i_requiitem"]) || $this->la34_i_requiitem != "")
           $resac = db_query("insert into db_acount values($acount,2894,16523,'".AddSlashes(pg_result($resaco,$conresaco,'la34_i_requiitem'))."','$this->la34_i_requiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_d_data"]) || $this->la34_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2894,16524,'".AddSlashes(pg_result($resaco,$conresaco,'la34_d_data'))."','$this->la34_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_c_hora"]) || $this->la34_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2894,16525,'".AddSlashes(pg_result($resaco,$conresaco,'la34_c_hora'))."','$this->la34_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_o_laudo"]) || $this->la34_o_laudo != "")
           $resac = db_query("insert into db_acount values($acount,2894,16526,'".AddSlashes(pg_result($resaco,$conresaco,'la34_o_laudo'))."','$this->la34_o_laudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la34_c_nomearq"]) || $this->la34_c_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,2894,16527,'".AddSlashes(pg_result($resaco,$conresaco,'la34_c_nomearq'))."','$this->la34_c_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emissão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la34_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emissão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la34_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la34_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16519,'$la34_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2894,16519,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16521,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_i_forma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16522,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16523,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_i_requiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16524,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16525,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16526,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_o_laudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2894,16527,'','".AddSlashes(pg_result($resaco,$iresaco,'la34_c_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_emissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la34_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la34_i_codigo = $la34_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Emissão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la34_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Emissão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la34_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_emissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_emissao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_emissao.la34_i_usuario";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = lab_emissao.la34_i_requiitem";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql2 = "";
     if($dbwhere==""){
       if($la34_i_codigo!=null ){
         $sql2 .= " where lab_emissao.la34_i_codigo = $la34_i_codigo "; 
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
   function sql_query_file ( $la34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_emissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($la34_i_codigo!=null ){
         $sql2 .= " where lab_emissao.la34_i_codigo = $la34_i_codigo "; 
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

     function sql_query_labsetor ( $la34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_emissao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_emissao.la34_i_usuario";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = lab_emissao.la34_i_requiitem";
     $sql .= "      inner join lab_setorexame  on  lab_setorexame.la09_i_codigo = lab_requiitem.la21_i_setorexame";
     $sql .= "      inner join lab_requisicao  on  lab_requisicao.la22_i_codigo = lab_requiitem.la21_i_requisicao";
     $sql .= "      left  join lab_labsetor    on  lab_labsetor.la24_i_codigo   = lab_setorexame.la09_i_labsetor";
     $sql .= "      left  join lab_setor       on  lab_setor.la23_i_codigo      = lab_labsetor.la24_i_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($la34_i_codigo!=null ){
         $sql2 .= " where lab_emissao.la34_i_codigo = $la34_i_codigo "; 
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