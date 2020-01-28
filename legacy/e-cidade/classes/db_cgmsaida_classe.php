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
//CLASSE DA ENTIDADE cgmsaida
class cl_cgmsaida { 
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
   var $sd28_i_codigo = 0; 
   var $sd28_i_cgm = 0; 
   var $sd28_i_departamento = 0; 
   var $sd28_i_material = 0; 
   var $sd28_i_quantidade = 0; 
   var $sd28_i_usuario = 0; 
   var $sd28_d_data_dia = null; 
   var $sd28_d_data_mes = null; 
   var $sd28_d_data_ano = null; 
   var $sd28_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd28_i_codigo = int8 = Código 
                 sd28_i_cgm = int8 = Cgm 
                 sd28_i_departamento = int8 = Departamento 
                 sd28_i_material = int8 = Material 
                 sd28_i_quantidade = int8 = Quantidade 
                 sd28_i_usuario = int8 = Usuario 
                 sd28_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_cgmsaida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cgmsaida"); 
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
       $this->sd28_i_codigo = ($this->sd28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_codigo"]:$this->sd28_i_codigo);
       $this->sd28_i_cgm = ($this->sd28_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_cgm"]:$this->sd28_i_cgm);
       $this->sd28_i_departamento = ($this->sd28_i_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_departamento"]:$this->sd28_i_departamento);
       $this->sd28_i_material = ($this->sd28_i_material == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_material"]:$this->sd28_i_material);
       $this->sd28_i_quantidade = ($this->sd28_i_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_quantidade"]:$this->sd28_i_quantidade);
       $this->sd28_i_usuario = ($this->sd28_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_usuario"]:$this->sd28_i_usuario);
       if($this->sd28_d_data == ""){
         $this->sd28_d_data_dia = ($this->sd28_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_d_data_dia"]:$this->sd28_d_data_dia);
         $this->sd28_d_data_mes = ($this->sd28_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_d_data_mes"]:$this->sd28_d_data_mes);
         $this->sd28_d_data_ano = ($this->sd28_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_d_data_ano"]:$this->sd28_d_data_ano);
         if($this->sd28_d_data_dia != ""){
            $this->sd28_d_data = $this->sd28_d_data_ano."-".$this->sd28_d_data_mes."-".$this->sd28_d_data_dia;
         }
       }
     }else{
       $this->sd28_i_codigo = ($this->sd28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd28_i_codigo"]:$this->sd28_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd28_i_codigo){ 
      $this->atualizacampos();
     if($this->sd28_i_cgm == null ){ 
       $this->erro_sql = " Campo Cgm nao Informado.";
       $this->erro_campo = "sd28_i_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd28_i_departamento == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "sd28_i_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd28_i_material == null ){ 
       $this->erro_sql = " Campo Material nao Informado.";
       $this->erro_campo = "sd28_i_material";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd28_i_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "sd28_i_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd28_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuario nao Informado.";
       $this->erro_campo = "sd28_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd28_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "sd28_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd28_i_codigo == "" || $sd28_i_codigo == null ){
       $result = @pg_query("select nextval('cgmsaida_sd28_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmsaida_sd28_i_codigo_seq do campo: sd28_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd28_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from cgmsaida_sd28_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd28_i_codigo)){
         $this->erro_sql = " Campo sd28_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd28_i_codigo = $sd28_i_codigo; 
       }
     }
     if(($this->sd28_i_codigo == null) || ($this->sd28_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd28_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmsaida(
                                       sd28_i_codigo 
                                      ,sd28_i_cgm 
                                      ,sd28_i_departamento 
                                      ,sd28_i_material 
                                      ,sd28_i_quantidade 
                                      ,sd28_i_usuario 
                                      ,sd28_d_data 
                       )
                values (
                                $this->sd28_i_codigo 
                               ,$this->sd28_i_cgm 
                               ,$this->sd28_i_departamento 
                               ,$this->sd28_i_material 
                               ,$this->sd28_i_quantidade 
                               ,$this->sd28_i_usuario 
                               ,".($this->sd28_d_data == "null" || $this->sd28_d_data == ""?"null":"'".$this->sd28_d_data."'")." 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Saída por Cgm ($this->sd28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saída por Cgm já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Saída por Cgm ($this->sd28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd28_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd28_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006130,'$this->sd28_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006130,'','".AddSlashes(pg_result($resaco,0,'sd28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006148,'','".AddSlashes(pg_result($resaco,0,'sd28_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006149,'','".AddSlashes(pg_result($resaco,0,'sd28_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006132,'','".AddSlashes(pg_result($resaco,0,'sd28_i_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006134,'','".AddSlashes(pg_result($resaco,0,'sd28_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006147,'','".AddSlashes(pg_result($resaco,0,'sd28_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006016,1006146,'','".AddSlashes(pg_result($resaco,0,'sd28_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd28_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cgmsaida set ";
     $virgula = "";
     if(trim($this->sd28_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_codigo"])){ 
       $sql  .= $virgula." sd28_i_codigo = $this->sd28_i_codigo ";
       $virgula = ",";
       if(trim($this->sd28_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd28_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd28_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_cgm"])){ 
       $sql  .= $virgula." sd28_i_cgm = $this->sd28_i_cgm ";
       $virgula = ",";
       if(trim($this->sd28_i_cgm) == null ){ 
         $this->erro_sql = " Campo Cgm nao Informado.";
         $this->erro_campo = "sd28_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd28_i_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_departamento"])){ 
       $sql  .= $virgula." sd28_i_departamento = $this->sd28_i_departamento ";
       $virgula = ",";
       if(trim($this->sd28_i_departamento) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "sd28_i_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd28_i_material)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_material"])){ 
       $sql  .= $virgula." sd28_i_material = $this->sd28_i_material ";
       $virgula = ",";
       if(trim($this->sd28_i_material) == null ){ 
         $this->erro_sql = " Campo Material nao Informado.";
         $this->erro_campo = "sd28_i_material";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd28_i_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_quantidade"])){ 
       $sql  .= $virgula." sd28_i_quantidade = $this->sd28_i_quantidade ";
       $virgula = ",";
       if(trim($this->sd28_i_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "sd28_i_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd28_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_usuario"])){ 
       $sql  .= $virgula." sd28_i_usuario = $this->sd28_i_usuario ";
       $virgula = ",";
       if(trim($this->sd28_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario nao Informado.";
         $this->erro_campo = "sd28_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd28_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd28_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd28_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd28_d_data = '$this->sd28_d_data' ";
       $virgula = ",";
       if(trim($this->sd28_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "sd28_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_d_data_dia"])){ 
         $sql  .= $virgula." sd28_d_data = null ";
         $virgula = ",";
         if(trim($this->sd28_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "sd28_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($sd28_i_codigo!=null){
       $sql .= " sd28_i_codigo = $this->sd28_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd28_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006130,'$this->sd28_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006130,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_i_codigo'))."','$this->sd28_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_cgm"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006148,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_i_cgm'))."','$this->sd28_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_departamento"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006149,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_i_departamento'))."','$this->sd28_i_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_material"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006132,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_i_material'))."','$this->sd28_i_material',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_quantidade"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006134,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_i_quantidade'))."','$this->sd28_i_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_i_usuario"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006147,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_i_usuario'))."','$this->sd28_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd28_d_data"]))
           $resac = pg_query("insert into db_acount values($acount,1006016,1006146,'".AddSlashes(pg_result($resaco,$conresaco,'sd28_d_data'))."','$this->sd28_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saída por Cgm nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saída por Cgm nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd28_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd28_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006130,'$sd28_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006130,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006148,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006149,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006132,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_i_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006134,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_i_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006147,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006016,1006146,'','".AddSlashes(pg_result($resaco,$iresaco,'sd28_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cgmsaida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd28_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd28_i_codigo = $sd28_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saída por Cgm nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Saída por Cgm nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd28_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmsaida";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmsaida ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = cgmsaida.sd28_i_cgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cgmsaida.sd28_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = cgmsaida.sd28_i_departamento";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = cgmsaida.sd28_i_material";
     $sql .= "      inner join db_depart  as a on   a.coddepto = matestoque.m70_coddepto";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoque.m70_codmatmater";
     $sql2 = "";
     if($dbwhere==""){
       if($sd28_i_codigo!=null ){
         $sql2 .= " where cgmsaida.sd28_i_codigo = $sd28_i_codigo "; 
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
   function sql_query_file ( $sd28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cgmsaida ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd28_i_codigo!=null ){
         $sql2 .= " where cgmsaida.sd28_i_codigo = $sd28_i_codigo "; 
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