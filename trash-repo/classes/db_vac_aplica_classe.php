<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: vacinas
//CLASSE DA ENTIDADE vac_aplica
class cl_vac_aplica { 
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
   var $vc16_i_codigo = 0; 
   var $vc16_i_dosevacina = 0; 
   var $vc16_n_quant = 0; 
   var $vc16_d_dataaplicada_dia = null; 
   var $vc16_d_dataaplicada_mes = null; 
   var $vc16_d_dataaplicada_ano = null; 
   var $vc16_d_dataaplicada = null; 
   var $vc16_t_obs = null; 
   var $vc16_i_usuario = 0; 
   var $vc16_i_departamento = 0; 
   var $vc16_c_hora = null; 
   var $vc16_d_data_dia = null; 
   var $vc16_d_data_mes = null; 
   var $vc16_d_data_ano = null; 
   var $vc16_d_data = null; 
   var $vc16_i_cgs = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc16_i_codigo = int4 = Código 
                 vc16_i_dosevacina = int4 = Vacina Dose 
                 vc16_n_quant = float4 = Quantidade 
                 vc16_d_dataaplicada = date = Data aplicação 
                 vc16_t_obs = text = Observação 
                 vc16_i_usuario = int4 = Usuário 
                 vc16_i_departamento = int4 = Departamento 
                 vc16_c_hora = char(5) = Hora 
                 vc16_d_data = date = Data 
                 vc16_i_cgs = int4 = CGS 
                 ";
   //funcao construtor da classe 
   function cl_vac_aplica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_aplica"); 
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
       $this->vc16_i_codigo = ($this->vc16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_i_codigo"]:$this->vc16_i_codigo);
       $this->vc16_i_dosevacina = ($this->vc16_i_dosevacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_i_dosevacina"]:$this->vc16_i_dosevacina);
       $this->vc16_n_quant = ($this->vc16_n_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_n_quant"]:$this->vc16_n_quant);
       if($this->vc16_d_dataaplicada == ""){
         $this->vc16_d_dataaplicada_dia = ($this->vc16_d_dataaplicada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada_dia"]:$this->vc16_d_dataaplicada_dia);
         $this->vc16_d_dataaplicada_mes = ($this->vc16_d_dataaplicada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada_mes"]:$this->vc16_d_dataaplicada_mes);
         $this->vc16_d_dataaplicada_ano = ($this->vc16_d_dataaplicada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada_ano"]:$this->vc16_d_dataaplicada_ano);
         if($this->vc16_d_dataaplicada_dia != ""){
            $this->vc16_d_dataaplicada = $this->vc16_d_dataaplicada_ano."-".$this->vc16_d_dataaplicada_mes."-".$this->vc16_d_dataaplicada_dia;
         }
       }
       $this->vc16_t_obs = ($this->vc16_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_t_obs"]:$this->vc16_t_obs);
       $this->vc16_i_usuario = ($this->vc16_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_i_usuario"]:$this->vc16_i_usuario);
       $this->vc16_i_departamento = ($this->vc16_i_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_i_departamento"]:$this->vc16_i_departamento);
       $this->vc16_c_hora = ($this->vc16_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_c_hora"]:$this->vc16_c_hora);
       if($this->vc16_d_data == ""){
         $this->vc16_d_data_dia = ($this->vc16_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_d_data_dia"]:$this->vc16_d_data_dia);
         $this->vc16_d_data_mes = ($this->vc16_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_d_data_mes"]:$this->vc16_d_data_mes);
         $this->vc16_d_data_ano = ($this->vc16_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_d_data_ano"]:$this->vc16_d_data_ano);
         if($this->vc16_d_data_dia != ""){
            $this->vc16_d_data = $this->vc16_d_data_ano."-".$this->vc16_d_data_mes."-".$this->vc16_d_data_dia;
         }
       }
       $this->vc16_i_cgs = ($this->vc16_i_cgs == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_i_cgs"]:$this->vc16_i_cgs);
     }else{
       $this->vc16_i_codigo = ($this->vc16_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc16_i_codigo"]:$this->vc16_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc16_i_codigo){ 
      $this->atualizacampos();
     if($this->vc16_i_dosevacina == null ){ 
       $this->erro_sql = " Campo Vacina Dose nao Informado.";
       $this->erro_campo = "vc16_i_dosevacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_n_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "vc16_n_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_d_dataaplicada == null ){ 
       $this->erro_sql = " Campo Data aplicação nao Informado.";
       $this->erro_campo = "vc16_d_dataaplicada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "vc16_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_i_departamento == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "vc16_i_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "vc16_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "vc16_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc16_i_cgs == null ){ 
       $this->erro_sql = " Campo CGS nao Informado.";
       $this->erro_campo = "vc16_i_cgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc16_i_codigo == "" || $vc16_i_codigo == null ){
       $result = db_query("select nextval('vac_aplica_vc16_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_aplica_vc16_i_codigo_seq do campo: vc16_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc16_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_aplica_vc16_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc16_i_codigo)){
         $this->erro_sql = " Campo vc16_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc16_i_codigo = $vc16_i_codigo; 
       }
     }
     if(($this->vc16_i_codigo == null) || ($this->vc16_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc16_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_aplica(
                                       vc16_i_codigo 
                                      ,vc16_i_dosevacina 
                                      ,vc16_n_quant 
                                      ,vc16_d_dataaplicada 
                                      ,vc16_t_obs 
                                      ,vc16_i_usuario 
                                      ,vc16_i_departamento 
                                      ,vc16_c_hora 
                                      ,vc16_d_data 
                                      ,vc16_i_cgs 
                       )
                values (
                                $this->vc16_i_codigo 
                               ,$this->vc16_i_dosevacina 
                               ,$this->vc16_n_quant 
                               ,".($this->vc16_d_dataaplicada == "null" || $this->vc16_d_dataaplicada == ""?"null":"'".$this->vc16_d_dataaplicada."'")." 
                               ,'$this->vc16_t_obs' 
                               ,$this->vc16_i_usuario 
                               ,$this->vc16_i_departamento 
                               ,'$this->vc16_c_hora' 
                               ,".($this->vc16_d_data == "null" || $this->vc16_d_data == ""?"null":"'".$this->vc16_d_data."'")." 
                               ,$this->vc16_i_cgs 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aplicar Vacina ($this->vc16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aplicar Vacina já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aplicar Vacina ($this->vc16_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc16_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc16_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16874,'$this->vc16_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2970,16874,'','".AddSlashes(pg_result($resaco,0,'vc16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16875,'','".AddSlashes(pg_result($resaco,0,'vc16_i_dosevacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16877,'','".AddSlashes(pg_result($resaco,0,'vc16_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16879,'','".AddSlashes(pg_result($resaco,0,'vc16_d_dataaplicada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16878,'','".AddSlashes(pg_result($resaco,0,'vc16_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16881,'','".AddSlashes(pg_result($resaco,0,'vc16_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16882,'','".AddSlashes(pg_result($resaco,0,'vc16_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16880,'','".AddSlashes(pg_result($resaco,0,'vc16_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,16876,'','".AddSlashes(pg_result($resaco,0,'vc16_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2970,17083,'','".AddSlashes(pg_result($resaco,0,'vc16_i_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc16_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_aplica set ";
     $virgula = "";
     if(trim($this->vc16_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_codigo"])){ 
       $sql  .= $virgula." vc16_i_codigo = $this->vc16_i_codigo ";
       $virgula = ",";
       if(trim($this->vc16_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc16_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc16_i_dosevacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_dosevacina"])){ 
       $sql  .= $virgula." vc16_i_dosevacina = $this->vc16_i_dosevacina ";
       $virgula = ",";
       if(trim($this->vc16_i_dosevacina) == null ){ 
         $this->erro_sql = " Campo Vacina Dose nao Informado.";
         $this->erro_campo = "vc16_i_dosevacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc16_n_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_n_quant"])){ 
       $sql  .= $virgula." vc16_n_quant = $this->vc16_n_quant ";
       $virgula = ",";
       if(trim($this->vc16_n_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "vc16_n_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc16_d_dataaplicada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada_dia"] !="") ){ 
       $sql  .= $virgula." vc16_d_dataaplicada = '$this->vc16_d_dataaplicada' ";
       $virgula = ",";
       if(trim($this->vc16_d_dataaplicada) == null ){ 
         $this->erro_sql = " Campo Data aplicação nao Informado.";
         $this->erro_campo = "vc16_d_dataaplicada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada_dia"])){ 
         $sql  .= $virgula." vc16_d_dataaplicada = null ";
         $virgula = ",";
         if(trim($this->vc16_d_dataaplicada) == null ){ 
           $this->erro_sql = " Campo Data aplicação nao Informado.";
           $this->erro_campo = "vc16_d_dataaplicada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vc16_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_t_obs"])){ 
       $sql  .= $virgula." vc16_t_obs = '$this->vc16_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->vc16_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_usuario"])){ 
       $sql  .= $virgula." vc16_i_usuario = $this->vc16_i_usuario ";
       $virgula = ",";
       if(trim($this->vc16_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "vc16_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc16_i_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_departamento"])){ 
       $sql  .= $virgula." vc16_i_departamento = $this->vc16_i_departamento ";
       $virgula = ",";
       if(trim($this->vc16_i_departamento) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "vc16_i_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc16_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_c_hora"])){ 
       $sql  .= $virgula." vc16_c_hora = '$this->vc16_c_hora' ";
       $virgula = ",";
       if(trim($this->vc16_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "vc16_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc16_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc16_d_data_dia"] !="") ){ 
       $sql  .= $virgula." vc16_d_data = '$this->vc16_d_data' ";
       $virgula = ",";
       if(trim($this->vc16_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "vc16_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_d_data_dia"])){ 
         $sql  .= $virgula." vc16_d_data = null ";
         $virgula = ",";
         if(trim($this->vc16_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "vc16_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vc16_i_cgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_cgs"])){ 
       $sql  .= $virgula." vc16_i_cgs = $this->vc16_i_cgs ";
       $virgula = ",";
       if(trim($this->vc16_i_cgs) == null ){ 
         $this->erro_sql = " Campo CGS nao Informado.";
         $this->erro_campo = "vc16_i_cgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc16_i_codigo!=null){
       $sql .= " vc16_i_codigo = $this->vc16_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc16_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16874,'$this->vc16_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_codigo"]) || $this->vc16_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2970,16874,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_i_codigo'))."','$this->vc16_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_dosevacina"]) || $this->vc16_i_dosevacina != "")
           $resac = db_query("insert into db_acount values($acount,2970,16875,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_i_dosevacina'))."','$this->vc16_i_dosevacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_n_quant"]) || $this->vc16_n_quant != "")
           $resac = db_query("insert into db_acount values($acount,2970,16877,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_n_quant'))."','$this->vc16_n_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_d_dataaplicada"]) || $this->vc16_d_dataaplicada != "")
           $resac = db_query("insert into db_acount values($acount,2970,16879,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_d_dataaplicada'))."','$this->vc16_d_dataaplicada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_t_obs"]) || $this->vc16_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2970,16878,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_t_obs'))."','$this->vc16_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_usuario"]) || $this->vc16_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2970,16881,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_i_usuario'))."','$this->vc16_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_departamento"]) || $this->vc16_i_departamento != "")
           $resac = db_query("insert into db_acount values($acount,2970,16882,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_i_departamento'))."','$this->vc16_i_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_c_hora"]) || $this->vc16_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2970,16880,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_c_hora'))."','$this->vc16_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_d_data"]) || $this->vc16_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2970,16876,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_d_data'))."','$this->vc16_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc16_i_cgs"]) || $this->vc16_i_cgs != "")
           $resac = db_query("insert into db_acount values($acount,2970,17083,'".AddSlashes(pg_result($resaco,$conresaco,'vc16_i_cgs'))."','$this->vc16_i_cgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aplicar Vacina nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aplicar Vacina nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc16_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc16_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16874,'$vc16_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2970,16874,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16875,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_i_dosevacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16877,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16879,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_d_dataaplicada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16878,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16881,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16882,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16880,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,16876,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2970,17083,'','".AddSlashes(pg_result($resaco,$iresaco,'vc16_i_cgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_aplica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc16_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc16_i_codigo = $vc16_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aplicar Vacina nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc16_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aplicar Vacina nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc16_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc16_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_aplica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplica ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_aplica.vc16_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vac_aplica.vc16_i_departamento";
     $sql .= "      inner join vac_vacinadose  on  vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina";
     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = vac_aplica.vc16_i_cgs";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_depart.id_usuarioresp";
     $sql .= "      inner join vac_dose  on  vac_dose.vc03_i_codigo = vac_vacinadose.vc07_i_dose";
     $sql .= "      inner join vac_calendario  on  vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario";
     $sql .= "      inner join vac_vacina  as a on   a.vc06_i_codigo = vac_vacinadose.vc07_i_vacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc16_i_codigo!=null ){
         $sql2 .= " where vac_aplica.vc16_i_codigo = $vc16_i_codigo "; 
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
   function sql_query_file ( $vc16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplica ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc16_i_codigo!=null ){
         $sql2 .= " where vac_aplica.vc16_i_codigo = $vc16_i_codigo "; 
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
   function sql_query2 ( $vc16_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_aplica ";
     $sql .= "      left join  vac_aplicalote on vac_aplicalote.vc17_i_aplica = vac_aplica.vc16_i_codigo";
     $sql .= "      left join  matestoqueitemlote on matestoqueitemlote.m77_sequencial = vac_aplicalote.vc17_i_matetoqueitemlote";
     $sql .= "      left join  matestoqueitem on matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join matestoque          on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque ";
     $sql .= "      left join  vac_sala on vac_sala.vc01_i_codigo = vac_aplicalote.vc17_i_sala";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_aplica.vc16_i_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = vac_aplica.vc16_i_departamento";
     $sql .= "      inner join vac_vacinadose  on  vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = vac_aplica.vc16_i_cgs";
     $sql .= "      inner join vac_dose  on  vac_dose.vc03_i_codigo = vac_vacinadose.vc07_i_dose";
     $sql .= "      inner join vac_calendario  on  vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario";
     $sql .= "      inner join vac_vacina   on   vac_vacina.vc06_i_codigo = vac_vacinadose.vc07_i_vacina";
     $sql .= "      inner join vac_vacinamaterial on vc29_i_vacina = vac_vacina.vc06_i_codigo";
     $sql .= "                                   and vc29_i_material = matestoque.m70_codmatmater";
     $sql .= "      inner join matmater on matmater.m60_codmater = vac_vacinamaterial.vc29_i_material";
     $sql .= "      left join  matunid on matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($vc16_i_codigo!=null ){
         $sql2 .= " where vac_aplica.vc16_i_codigo = $vc16_i_codigo "; 
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
   function sql_query_vacinasnaoaplicadas($sCampos = '*', $sOrdem = null, $sGroup = '', 
                                         $dDataInicial, $dDataFinal, $sVacinas, $dFaixaIni, $dFaixaFim) { 

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql   .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql  .= " from ( ";
    $sSql  .= " select *,"; 
    $sSql  .= " case when vc07_i_tipocalculo = 1 or ultimaAplicacao is null then ";
    $sSql  .= " now()::date - (z01_d_nasc + (vc07_i_faixainiano||' years')::INTERVAL + ";
    $sSql  .= " (vc07_i_faixainimes||' months')::INTERVAL + (vc07_i_faixainidias||' days')::INTERVAL)::Date ";
    $sSql  .= " when vc07_i_tipocalculo = 2 or vc07_i_tipocalculo = 3 then ";
    $sSql  .= " now()::date - (ultimaAplicacao + ";
    $sSql  .= " (sumproxdias||' days')::INTERVAL + ";
    $sSql  .= " (sumproxmeses||' months')::INTERVAL + ";
    $sSql  .= " (sumproxanos||' years')::INTERVAL)::Date ";
    $sSql  .= " end as diasAtraso ";
    $sSql  .= " from (select * , "; 
    /* -- ULTIMA DOSE -- */ 
    $sSql  .= " (select vc16_d_data ";  
    $sSql  .= " from vac_aplica "; 
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina "; 
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose "; 
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina "; 
    $sSql  .= " where vc07_i_vacina = vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo and ";
    $sSql  .= " z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " order by vc03_i_ordem desc limit 1) as ultimaAplicacao, ";
    /* -- FIM ULTIMA DOSE -- */
    /* -- INICIO DE CALCULO DAS FAIXAS PARA AS VACINAS -- */
    /* --|INICIO DIAS|-- */
    $sSql  .= " (select sum(vc07_i_faixainidias) ";
    $sSql  .= " from vac_vacinadose ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";  
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina "; 
    $sSql  .= " where vac_dose.vc03_i_ordem > "; 
    $sSql  .= " (select max(vc03_i_ordem) ";  
    $sSql  .= " from vac_aplica "; 
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina "; 
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina "; 
    $sSql  .= " where vac_vacinadose.vc07_i_vacina =  vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo "; 
    $sSql  .= " and cgs_und.z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " ) "; 
    $sSql  .= " and vac_dose.vc03_i_ordem <= vacinasNaoAplicadasPeriodoIndefinido.vc03_i_ordem ";
    $sSql  .= " and vac_dose.vc03_i_ordem != "; 
    $sSql  .= " (select max(vc03_i_ordem) ";  
    $sSql  .= " from vac_aplica "; 
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_vacinadose.vc07_i_vacina =  vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and cgs_und.z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " ) ";
    $sSql  .= " and vac_vacinadose.vc07_i_tipocalculo != 1 ";
    $sSql  .= " and vac_vacinadose.vc07_i_vacina = vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and vac_vacinadose.vc07_i_codigo = vacinasNaoAplicadasPeriodoIndefinido.vc07_i_codigo) as sumProxDias, ";
    /* --|FIM DIAS|-- */
    /* --|INICIO MESES|-- */
    $sSql  .= " (select sum(vc07_i_faixainimes) ";
    $sSql  .= " from vac_vacinadose ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_dose.vc03_i_ordem > ";
    $sSql  .= " (select max(vc03_i_ordem) "; 
    $sSql  .= " from vac_aplica ";
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_vacinadose.vc07_i_vacina =  vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo "; 
    $sSql  .= " and cgs_und.z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " ) "; 
    $sSql  .= " and vac_dose.vc03_i_ordem <= vacinasNaoAplicadasPeriodoIndefinido.vc03_i_ordem ";
    $sSql  .= " and vac_dose.vc03_i_ordem != ";
    $sSql  .= " (select max(vc03_i_ordem)  ";
    $sSql  .= " from vac_aplica ";
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_vacinadose.vc07_i_vacina =  vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and cgs_und.z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " ) ";
    $sSql  .= " and vac_vacinadose.vc07_i_tipocalculo != 1 ";
    $sSql  .= " and vac_vacinadose.vc07_i_vacina = vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and vac_vacinadose.vc07_i_codigo = vacinasNaoAplicadasPeriodoIndefinido.vc07_i_codigo) as sumProxMeses,";
    /* --|FIM MESES|-- */
    /* --|INICIO ANOS|-- */
    $sSql  .= " (select sum(vc07_i_faixainiano) ";
    $sSql  .= " from vac_vacinadose ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_dose.vc03_i_ordem > ";
    $sSql  .= " (select max(vc03_i_ordem)  ";
    $sSql  .= " from vac_aplica ";
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs "; 
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_vacinadose.vc07_i_vacina =  vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and cgs_und.z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " ) "; 
    $sSql  .= " and vac_dose.vc03_i_ordem <= vacinasNaoAplicadasPeriodoIndefinido.vc03_i_ordem ";
    $sSql  .= " and vac_dose.vc03_i_ordem != ";
    $sSql  .= " (select max(vc03_i_ordem) ";
    $sSql  .= " from vac_aplica ";
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vac_vacinadose.vc07_i_vacina =  vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and cgs_und.z01_i_cgsund = vacinasNaoAplicadasPeriodoIndefinido.z01_i_cgsund ";
    $sSql  .= " ) ";
    $sSql  .= " and vac_vacinadose.vc07_i_tipocalculo != 1 ";
    $sSql  .= " and vac_vacinadose.vc07_i_vacina = vacinasNaoAplicadasPeriodoIndefinido.vc06_i_codigo ";
    $sSql  .= " and vac_vacinadose.vc07_i_codigo = vacinasNaoAplicadasPeriodoIndefinido.vc07_i_codigo) as sumProxAnos ";
    /* --|FIM ANOS|-- */
    /* -- FIM CALCULO DAS FAIXAS PARA AS VACINAS -- */
    $sSql  .= " from ";
    $sSql  .= " (select * ";
    $sSql  .= " from "; 
    $sSql  .= " (select * from ";
    /* -- PLANO CARTESIANO PESSOAS x VACINAS x DOSES -- */
    $sSql  .= " (select vc06_i_codigo, vc06_c_descr, vc07_i_codigo, vc07_i_faixainidias, ";
    $sSql  .= " vc07_i_faixainimes, vc07_i_faixainiano, vc03_c_descr, vc03_i_ordem,vc07_i_tipocalculo, vc07_i_calendario"; 
    $sSql  .= " from vac_vacina ";
    $sSql  .= " inner join vac_vacinadose on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina "; 
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " where vc07_i_vacina in ($sVacinas) ";
    $sSql  .= " order by vc06_i_codigo, vc03_i_codigo) as dosesVacinasRequeridas , ";
    $sSql  .= " (select distinct z01_i_cgsund, z01_d_nasc, z01_v_nome, z01_v_bairro ";
    $sSql  .= " from vac_aplica ";
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vc07_i_vacina in ($sVacinas)  ";
    $sSql  .= " and z01_d_nasc between '$dFaixaIni'::Date and '$dFaixaFim'::Date ";
    $sSql  .= " order by z01_i_cgsund) as pessoas) as produtoPessoasVacinasDoses ";
    /* -- FIM PLANO CARTESIANO PESSOAS x VACINAS x DOSES -- */
    $sSql  .= " except ";
    /* -- VACINAS APLICADAS -- */
    $sSql  .= " (select vc06_i_codigo, vc06_c_descr, vc07_i_codigo,vc07_i_faixainidias,vc07_i_faixainimes, ";
    $sSql  .= " vc07_i_faixainiano,vc03_c_descr, vc03_i_ordem, vc07_i_tipocalculo, vc07_i_calendario,";
    $sSql  .= " z01_i_cgsund, z01_d_nasc, z01_v_nome, z01_v_bairro ";
    $sSql  .= " from vac_aplica "; 
    $sSql  .= " inner join vac_vacinadose on vac_vacinadose.vc07_i_codigo = vac_aplica.vc16_i_dosevacina ";
    $sSql  .= " inner join cgs_und        on cgs_und.z01_i_cgsund         = vac_aplica.vc16_i_cgs ";
    $sSql  .= " inner join vac_dose       on vac_dose.vc03_i_codigo       = vac_vacinadose.vc07_i_dose ";
    $sSql  .= " inner join vac_calendario on vac_calendario.vc05_i_codigo = vac_vacinadose.vc07_i_calendario ";
    $sSql  .= " inner join vac_vacina     on vac_vacina.vc06_i_codigo     = vac_vacinadose.vc07_i_vacina ";
    $sSql  .= " where vc07_i_vacina in ($sVacinas)) ";
    /* -- FIM VACINAS APLICADAS -- */
    $sSql  .= " ) as vacinasNaoAplicadasPeriodoIndefinido ";
    $sSql  .= " ) as vacinasNaoAplicadas ";
    $sSql  .= " ) as faltosos";
    $sSql2  = " where ";
    $sSql2 .= " case when vc07_i_tipocalculo = 1 or ultimaAplicacao is null then ";
    $sSql2 .= " (z01_d_nasc + ";
    $sSql2 .= " (vc07_i_faixainiano||' years')::INTERVAL + ";
    $sSql2 .= " (vc07_i_faixainimes||' months')::INTERVAL + ";
    $sSql2 .= " (vc07_i_faixainidias||' days')::INTERVAL)::Date ";
    $sSql2 .= " when vc07_i_tipocalculo = 2 or vc07_i_tipocalculo = 3 then ";
    $sSql2 .= " (ultimaAplicacao + ";
    $sSql2 .= " (sumproxdias||' days')::INTERVAL + ";
    $sSql2 .= " (sumproxmeses||' months')::INTERVAL + ";
    $sSql2 .= " (sumproxanos||' years')::INTERVAL)::Date ";
    $sSql2 .= " end "; 
    $sSql2 .= " between '$dDataInicial'::date and '$dDataFinal'::Date ";
    $sSql2 .= $sGroup; 
    $sSql  .= $sSql2;
    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }
    return $sSql;

  }
}
?>