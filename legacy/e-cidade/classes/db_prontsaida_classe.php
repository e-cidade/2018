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

//MODULO: saude
//CLASSE DA ENTIDADE prontsaida
class cl_prontsaida { 
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
   var $sd27_i_codigo = 0; 
   var $sd27_i_prontuario = 0; 
   var $sd27_i_departamento = 0; 
   var $sd27_i_material = 0; 
   var $sd27_i_quantidade = 0; 
   var $sd27_i_usuario = 0; 
   var $sd27_d_data_dia = null; 
   var $sd27_d_data_mes = null; 
   var $sd27_d_data_ano = null; 
   var $sd27_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd27_i_codigo = int4 = Código 
                 sd27_i_prontuario = int4 = Prontuario 
                 sd27_i_departamento = int4 = Departamento 
                 sd27_i_material = int4 = Material 
                 sd27_i_quantidade = int4 = Quantidade 
                 sd27_i_usuario = int4 = Usuario 
                 sd27_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_prontsaida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontsaida"); 
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
       $this->sd27_i_codigo = ($this->sd27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"]:$this->sd27_i_codigo);
       $this->sd27_i_prontuario = ($this->sd27_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_prontuario"]:$this->sd27_i_prontuario);
       $this->sd27_i_departamento = ($this->sd27_i_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_departamento"]:$this->sd27_i_departamento);
       $this->sd27_i_material = ($this->sd27_i_material == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_material"]:$this->sd27_i_material);
       $this->sd27_i_quantidade = ($this->sd27_i_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_quantidade"]:$this->sd27_i_quantidade);
       $this->sd27_i_usuario = ($this->sd27_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_usuario"]:$this->sd27_i_usuario);
       if($this->sd27_d_data == ""){
         $this->sd27_d_data_dia = ($this->sd27_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_d_data_dia"]:$this->sd27_d_data_dia);
         $this->sd27_d_data_mes = ($this->sd27_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_d_data_mes"]:$this->sd27_d_data_mes);
         $this->sd27_d_data_ano = ($this->sd27_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_d_data_ano"]:$this->sd27_d_data_ano);
         if($this->sd27_d_data_dia != ""){
            $this->sd27_d_data = $this->sd27_d_data_ano."-".$this->sd27_d_data_mes."-".$this->sd27_d_data_dia;
         }
       }
     }else{
       $this->sd27_i_codigo = ($this->sd27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"]:$this->sd27_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd27_i_codigo){ 
      $this->atualizacampos();
     if($this->sd27_i_prontuario == null ){ 
       $this->erro_sql = " Campo Prontuario nao Informado.";
       $this->erro_campo = "sd27_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_i_departamento == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "sd27_i_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_i_material == null ){ 
       $this->erro_sql = " Campo Material nao Informado.";
       $this->erro_campo = "sd27_i_material";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_i_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "sd27_i_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "sd27_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "sd27_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd27_i_codigo == "" || $sd27_i_codigo == null ){
       $result = db_query("select nextval('prontsaida_sd27_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontsaida_sd27_i_codigo_seq do campo: sd27_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd27_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontsaida_sd27_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd27_i_codigo)){
         $this->erro_sql = " Campo sd27_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd27_i_codigo = $sd27_i_codigo; 
       }
     }
     if(($this->sd27_i_codigo == null) || ($this->sd27_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd27_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontsaida(
                                       sd27_i_codigo 
                                      ,sd27_i_prontuario 
                                      ,sd27_i_departamento 
                                      ,sd27_i_material 
                                      ,sd27_i_quantidade 
                                      ,sd27_i_usuario 
                                      ,sd27_d_data 
                       )
                values (
                                $this->sd27_i_codigo 
                               ,$this->sd27_i_prontuario 
                               ,$this->sd27_i_departamento 
                               ,$this->sd27_i_material 
                               ,$this->sd27_i_quantidade 
                               ,$this->sd27_i_usuario 
                               ,".($this->sd27_d_data == "null" || $this->sd27_d_data == ""?"null":"'".$this->sd27_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saida por Prontuarios ($this->sd27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saida por Prontuarios já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saida por Prontuarios ($this->sd27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd27_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1006141,'$this->sd27_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1006015,1006141,'','".AddSlashes(pg_result($resaco,0,'sd27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006015,1006131,'','".AddSlashes(pg_result($resaco,0,'sd27_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006015,1006133,'','".AddSlashes(pg_result($resaco,0,'sd27_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006015,1006142,'','".AddSlashes(pg_result($resaco,0,'sd27_i_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006015,1006143,'','".AddSlashes(pg_result($resaco,0,'sd27_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006015,1006145,'','".AddSlashes(pg_result($resaco,0,'sd27_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1006015,1006144,'','".AddSlashes(pg_result($resaco,0,'sd27_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd27_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontsaida set ";
     $virgula = "";
     if(trim($this->sd27_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"])){ 
       $sql  .= $virgula." sd27_i_codigo = $this->sd27_i_codigo ";
       $virgula = ",";
       if(trim($this->sd27_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd27_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_prontuario"])){ 
       $sql  .= $virgula." sd27_i_prontuario = $this->sd27_i_prontuario ";
       $virgula = ",";
       if(trim($this->sd27_i_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontuario nao Informado.";
         $this->erro_campo = "sd27_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_departamento"])){ 
       $sql  .= $virgula." sd27_i_departamento = $this->sd27_i_departamento ";
       $virgula = ",";
       if(trim($this->sd27_i_departamento) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "sd27_i_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_material)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_material"])){ 
       $sql  .= $virgula." sd27_i_material = $this->sd27_i_material ";
       $virgula = ",";
       if(trim($this->sd27_i_material) == null ){ 
         $this->erro_sql = " Campo Material nao Informado.";
         $this->erro_campo = "sd27_i_material";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_quantidade"])){ 
       $sql  .= $virgula." sd27_i_quantidade = $this->sd27_i_quantidade ";
       $virgula = ",";
       if(trim($this->sd27_i_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "sd27_i_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_usuario"])){ 
       $sql  .= $virgula." sd27_i_usuario = $this->sd27_i_usuario ";
       $virgula = ",";
       if(trim($this->sd27_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "sd27_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd27_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd27_d_data = '$this->sd27_d_data' ";
       $virgula = ",";
       if(trim($this->sd27_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "sd27_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_d_data_dia"])){ 
         $sql  .= $virgula." sd27_d_data = null ";
         $virgula = ",";
         if(trim($this->sd27_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "sd27_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($sd27_i_codigo!=null){
       $sql .= " sd27_i_codigo = $this->sd27_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd27_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1006141,'$this->sd27_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006141,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_codigo'))."','$this->sd27_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_prontuario"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006131,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_prontuario'))."','$this->sd27_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_departamento"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006133,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_departamento'))."','$this->sd27_i_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_material"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006142,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_material'))."','$this->sd27_i_material',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_quantidade"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006143,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_quantidade'))."','$this->sd27_i_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006145,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_usuario'))."','$this->sd27_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1006015,1006144,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_d_data'))."','$this->sd27_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saida por Prontuarios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saida por Prontuarios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd27_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd27_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1006141,'$sd27_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1006015,1006141,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006015,1006131,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006015,1006133,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006015,1006142,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006015,1006143,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006015,1006145,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1006015,1006144,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prontsaida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd27_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd27_i_codigo = $sd27_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saida por Prontuarios nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saida por Prontuarios nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd27_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontsaida";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontsaida ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontsaida.sd27_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = prontsaida.sd27_i_departamento";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = prontsaida.sd27_i_material";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_id = prontsaida.sd27_i_prontuario";
     $sql .= "      inner join db_depart  as a on   a.coddepto = matestoque.m70_coddepto";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = prontuarios.sd24_i_cgm";
     $sql .= "      inner join db_usuarios as db_usuarios2 on  db_usuarios2.id_usuario = prontuarios.sd24_i_usuario";
     $sql .= "      inner join cids  on  cids.sd22_c_codigo = prontuarios.sd24_c_cid";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
     $sql.= "      inner join grupoatend  on  grupoatend.sd15_i_codigo = prontuarios.sd24_i_grupoatend";
     $sql2 = "";
     if($dbwhere==""){
       if($sd27_i_codigo!=null ){
         $sql2 .= " where prontsaida.sd27_i_codigo = $sd27_i_codigo "; 
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
   function sql_query_file ( $sd27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontsaida ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd27_i_codigo!=null ){
         $sql2 .= " where prontsaida.sd27_i_codigo = $sd27_i_codigo "; 
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