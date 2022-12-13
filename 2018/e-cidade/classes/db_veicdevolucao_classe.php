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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicdevolucao
class cl_veicdevolucao { 
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
   var $ve61_codigo = 0; 
   var $ve61_veicretirada = 0; 
   var $ve61_veicmotoristas = 0; 
   var $ve61_datadevol_dia = null; 
   var $ve61_datadevol_mes = null; 
   var $ve61_datadevol_ano = null; 
   var $ve61_datadevol = null; 
   var $ve61_horadevol = null; 
   var $ve61_usuario = 0; 
   var $ve61_data_dia = null; 
   var $ve61_data_mes = null; 
   var $ve61_data_ano = null; 
   var $ve61_data = null; 
   var $ve61_hora = null; 
   var $ve61_medidadevol = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve61_codigo = int4 = Código Devolução 
                 ve61_veicretirada = int4 = Código Retirada 
                 ve61_veicmotoristas = int4 = Motorista 
                 ve61_datadevol = date = Data Devolução 
                 ve61_horadevol = char(5) = Hora Devolução 
                 ve61_usuario = int4 = Usuário 
                 ve61_data = date = Data 
                 ve61_hora = char(5) = Hora 
                 ve61_medidadevol = float8 = Medida de devolução 
                 ";
   //funcao construtor da classe 
   function cl_veicdevolucao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicdevolucao"); 
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
       $this->ve61_codigo = ($this->ve61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_codigo"]:$this->ve61_codigo);
       $this->ve61_veicretirada = ($this->ve61_veicretirada == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_veicretirada"]:$this->ve61_veicretirada);
       $this->ve61_veicmotoristas = ($this->ve61_veicmotoristas == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_veicmotoristas"]:$this->ve61_veicmotoristas);
       if($this->ve61_datadevol == ""){
         $this->ve61_datadevol_dia = ($this->ve61_datadevol_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_datadevol_dia"]:$this->ve61_datadevol_dia);
         $this->ve61_datadevol_mes = ($this->ve61_datadevol_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_datadevol_mes"]:$this->ve61_datadevol_mes);
         $this->ve61_datadevol_ano = ($this->ve61_datadevol_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_datadevol_ano"]:$this->ve61_datadevol_ano);
         if($this->ve61_datadevol_dia != ""){
            $this->ve61_datadevol = $this->ve61_datadevol_ano."-".$this->ve61_datadevol_mes."-".$this->ve61_datadevol_dia;
         }
       }
       $this->ve61_horadevol = ($this->ve61_horadevol == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_horadevol"]:$this->ve61_horadevol);
       $this->ve61_usuario = ($this->ve61_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_usuario"]:$this->ve61_usuario);
       if($this->ve61_data == ""){
         $this->ve61_data_dia = ($this->ve61_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_data_dia"]:$this->ve61_data_dia);
         $this->ve61_data_mes = ($this->ve61_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_data_mes"]:$this->ve61_data_mes);
         $this->ve61_data_ano = ($this->ve61_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_data_ano"]:$this->ve61_data_ano);
         if($this->ve61_data_dia != ""){
            $this->ve61_data = $this->ve61_data_ano."-".$this->ve61_data_mes."-".$this->ve61_data_dia;
         }
       }
       $this->ve61_hora = ($this->ve61_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_hora"]:$this->ve61_hora);
       $this->ve61_medidadevol = ($this->ve61_medidadevol == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_medidadevol"]:$this->ve61_medidadevol);
     }else{
       $this->ve61_codigo = ($this->ve61_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve61_codigo"]:$this->ve61_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ve61_codigo){ 
      $this->atualizacampos();
     if($this->ve61_veicretirada == null ){ 
       $this->erro_sql = " Campo Código Retirada nao Informado.";
       $this->erro_campo = "ve61_veicretirada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_veicmotoristas == null ){ 
       $this->erro_sql = " Campo Motorista nao Informado.";
       $this->erro_campo = "ve61_veicmotoristas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_datadevol == null ){ 
       $this->erro_sql = " Campo Data Devolução nao Informado.";
       $this->erro_campo = "ve61_datadevol_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_horadevol == null ){ 
       $this->erro_sql = " Campo Hora Devolução nao Informado.";
       $this->erro_campo = "ve61_horadevol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ve61_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ve61_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ve61_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve61_medidadevol == null ){ 
       $this->erro_sql = " Campo Medida de devolução nao Informado.";
       $this->erro_campo = "ve61_medidadevol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($ve61_codigo == "" || $ve61_codigo == null ){
       $result = db_query("select nextval('veicdevolucao_ve61_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicdevolucao_ve61_codigo_seq do campo: ve61_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve61_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veicdevolucao_ve61_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve61_codigo)){
         $this->erro_sql = " Campo ve61_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve61_codigo = $ve61_codigo; 
       }
     }
     if(($this->ve61_codigo == null) || ($this->ve61_codigo == "") ){ 
       $this->erro_sql = " Campo ve61_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicdevolucao(
                                       ve61_codigo 
                                      ,ve61_veicretirada 
                                      ,ve61_veicmotoristas 
                                      ,ve61_datadevol 
                                      ,ve61_horadevol 
                                      ,ve61_usuario 
                                      ,ve61_data 
                                      ,ve61_hora 
                                      ,ve61_medidadevol 
                       )
                values (
                                $this->ve61_codigo 
                               ,$this->ve61_veicretirada 
                               ,$this->ve61_veicmotoristas 
                               ,".($this->ve61_datadevol == "null" || $this->ve61_datadevol == ""?"null":"'".$this->ve61_datadevol."'")." 
                               ,'$this->ve61_horadevol' 
                               ,$this->ve61_usuario 
                               ,".($this->ve61_data == "null" || $this->ve61_data == ""?"null":"'".$this->ve61_data."'")." 
                               ,'$this->ve61_hora' 
                               ,$this->ve61_medidadevol 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Devolucao dos Veículos Retirados ($this->ve61_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Devolucao dos Veículos Retirados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Devolucao dos Veículos Retirados ($this->ve61_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve61_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve61_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9291,'$this->ve61_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1596,9291,'','".AddSlashes(pg_result($resaco,0,'ve61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9292,'','".AddSlashes(pg_result($resaco,0,'ve61_veicretirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9293,'','".AddSlashes(pg_result($resaco,0,'ve61_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9294,'','".AddSlashes(pg_result($resaco,0,'ve61_datadevol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9295,'','".AddSlashes(pg_result($resaco,0,'ve61_horadevol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9297,'','".AddSlashes(pg_result($resaco,0,'ve61_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9298,'','".AddSlashes(pg_result($resaco,0,'ve61_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,9299,'','".AddSlashes(pg_result($resaco,0,'ve61_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1596,11082,'','".AddSlashes(pg_result($resaco,0,'ve61_medidadevol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve61_codigo=null) { 
      $this->atualizacampos();
     $sql = " update veicdevolucao set ";
     $virgula = "";
     if(trim($this->ve61_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_codigo"])){ 
       $sql  .= $virgula." ve61_codigo = $this->ve61_codigo ";
       $virgula = ",";
       if(trim($this->ve61_codigo) == null ){ 
         $this->erro_sql = " Campo Código Devolução nao Informado.";
         $this->erro_campo = "ve61_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve61_veicretirada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_veicretirada"])){ 
       $sql  .= $virgula." ve61_veicretirada = $this->ve61_veicretirada ";
       $virgula = ",";
       if(trim($this->ve61_veicretirada) == null ){ 
         $this->erro_sql = " Campo Código Retirada nao Informado.";
         $this->erro_campo = "ve61_veicretirada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve61_veicmotoristas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_veicmotoristas"])){ 
       $sql  .= $virgula." ve61_veicmotoristas = $this->ve61_veicmotoristas ";
       $virgula = ",";
       if(trim($this->ve61_veicmotoristas) == null ){ 
         $this->erro_sql = " Campo Motorista nao Informado.";
         $this->erro_campo = "ve61_veicmotoristas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve61_datadevol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_datadevol_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve61_datadevol_dia"] !="") ){ 
       $sql  .= $virgula." ve61_datadevol = '$this->ve61_datadevol' ";
       $virgula = ",";
       if(trim($this->ve61_datadevol) == null ){ 
         $this->erro_sql = " Campo Data Devolução nao Informado.";
         $this->erro_campo = "ve61_datadevol_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_datadevol_dia"])){ 
         $sql  .= $virgula." ve61_datadevol = null ";
         $virgula = ",";
         if(trim($this->ve61_datadevol) == null ){ 
           $this->erro_sql = " Campo Data Devolução nao Informado.";
           $this->erro_campo = "ve61_datadevol_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve61_horadevol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_horadevol"])){ 
       $sql  .= $virgula." ve61_horadevol = '$this->ve61_horadevol' ";
       $virgula = ",";
       if(trim($this->ve61_horadevol) == null ){ 
         $this->erro_sql = " Campo Hora Devolução nao Informado.";
         $this->erro_campo = "ve61_horadevol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve61_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_usuario"])){ 
       $sql  .= $virgula." ve61_usuario = $this->ve61_usuario ";
       $virgula = ",";
       if(trim($this->ve61_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ve61_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve61_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve61_data_dia"] !="") ){ 
       $sql  .= $virgula." ve61_data = '$this->ve61_data' ";
       $virgula = ",";
       if(trim($this->ve61_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ve61_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_data_dia"])){ 
         $sql  .= $virgula." ve61_data = null ";
         $virgula = ",";
         if(trim($this->ve61_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ve61_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ve61_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_hora"])){ 
       $sql  .= $virgula." ve61_hora = '$this->ve61_hora' ";
       $virgula = ",";
       if(trim($this->ve61_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ve61_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve61_medidadevol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve61_medidadevol"])){ 
       $sql  .= $virgula." ve61_medidadevol = $this->ve61_medidadevol ";
       $virgula = ",";
       if(trim($this->ve61_medidadevol) == null ){ 
         $this->erro_sql = " Campo Medida de devolução nao Informado.";
         $this->erro_campo = "ve61_medidadevol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve61_codigo!=null){
       $sql .= " ve61_codigo = $this->ve61_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve61_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9291,'$this->ve61_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1596,9291,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_codigo'))."','$this->ve61_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_veicretirada"]))
           $resac = db_query("insert into db_acount values($acount,1596,9292,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_veicretirada'))."','$this->ve61_veicretirada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_veicmotoristas"]))
           $resac = db_query("insert into db_acount values($acount,1596,9293,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_veicmotoristas'))."','$this->ve61_veicmotoristas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_datadevol"]))
           $resac = db_query("insert into db_acount values($acount,1596,9294,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_datadevol'))."','$this->ve61_datadevol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_horadevol"]))
           $resac = db_query("insert into db_acount values($acount,1596,9295,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_horadevol'))."','$this->ve61_horadevol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1596,9297,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_usuario'))."','$this->ve61_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_data"]))
           $resac = db_query("insert into db_acount values($acount,1596,9298,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_data'))."','$this->ve61_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_hora"]))
           $resac = db_query("insert into db_acount values($acount,1596,9299,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_hora'))."','$this->ve61_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve61_medidadevol"]))
           $resac = db_query("insert into db_acount values($acount,1596,11082,'".AddSlashes(pg_result($resaco,$conresaco,'ve61_medidadevol'))."','$this->ve61_medidadevol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolucao dos Veículos Retirados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve61_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devolucao dos Veículos Retirados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve61_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve61_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve61_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve61_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9291,'$ve61_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1596,9291,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9292,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_veicretirada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9293,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_veicmotoristas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9294,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_datadevol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9295,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_horadevol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9297,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9298,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,9299,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1596,11082,'','".AddSlashes(pg_result($resaco,$iresaco,'ve61_medidadevol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veicdevolucao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve61_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve61_codigo = $ve61_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolucao dos Veículos Retirados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve61_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devolucao dos Veículos Retirados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve61_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve61_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicdevolucao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ve61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicdevolucao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = veicdevolucao.ve61_usuario";
     $sql .= "      inner join veicmotoristas  on  veicmotoristas.ve05_codigo = veicdevolucao.ve61_veicmotoristas";
     $sql .= "      inner join veicretirada  on  veicretirada.ve60_codigo = veicdevolucao.ve61_veicretirada";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = veicmotoristas.ve05_numcgm";
     $sql .= "      inner join veiccadcategcnh  on  veiccadcategcnh.ve30_codigo = veicmotoristas.ve05_veiccadcategcnh";
     $sql .= "      inner join veiccadmotoristasit  on  veiccadmotoristasit.ve33_codigo = veicmotoristas.ve05_veiccadmotoristasit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = veicretirada.ve60_coddepto";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicretirada.ve60_veiculo";
     $sql .= "      inner join veicmotoristas  as a on   a.ve05_codigo = veicretirada.ve60_veicmotoristas";
     $sql2 = "";
     if($dbwhere==""){
       if($ve61_codigo!=null ){
         $sql2 .= " where veicdevolucao.ve61_codigo = $ve61_codigo "; 
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
   function sql_query_file ( $ve61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicdevolucao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve61_codigo!=null ){
         $sql2 .= " where veicdevolucao.ve61_codigo = $ve61_codigo "; 
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
   function sql_query_medida ( $ve61_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from veicdevolucao ";
     $sql .= "  inner join veicretirada on veicretirada.ve60_codigo = veicdevolucao.ve61_veicretirada   ";
     $sql .= "  inner join veiculos on veiculos.ve01_codigo         = veicretirada.ve60_veiculo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve61_codigo!=null ){
         $sql2 .= " where veicdevolucao.ve61_codigo = $ve61_codigo ";
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