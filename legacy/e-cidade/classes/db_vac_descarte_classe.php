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
//CLASSE DA ENTIDADE vac_descarte
class cl_vac_descarte { 
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
   var $vc19_i_codigo = 0; 
   var $vc19_i_vacina = 0; 
   var $vc19_n_quant = 0; 
   var $vc19_t_obs = null; 
   var $vc19_i_usuario = 0; 
   var $vc19_d_data_dia = null; 
   var $vc19_d_data_mes = null; 
   var $vc19_d_data_ano = null; 
   var $vc19_d_data = null; 
   var $vc19_c_hora = null; 
   var $vc19_i_matetoqueitemlote = 0; 
   var $vc19_i_sala = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc19_i_codigo = int4 = Código 
                 vc19_i_vacina = int4 = Vacina 
                 vc19_n_quant = float4 = Quantidade 
                 vc19_t_obs = text = Observação 
                 vc19_i_usuario = int4 = Usuário 
                 vc19_d_data = date = Data 
                 vc19_c_hora = char(5) = Hora 
                 vc19_i_matetoqueitemlote = int4 = Lote 
                 vc19_i_sala = int4 = Sala 
                 ";
   //funcao construtor da classe 
   function cl_vac_descarte() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_descarte"); 
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
       $this->vc19_i_codigo = ($this->vc19_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_i_codigo"]:$this->vc19_i_codigo);
       $this->vc19_i_vacina = ($this->vc19_i_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_i_vacina"]:$this->vc19_i_vacina);
       $this->vc19_n_quant = ($this->vc19_n_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_n_quant"]:$this->vc19_n_quant);
       $this->vc19_t_obs = ($this->vc19_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_t_obs"]:$this->vc19_t_obs);
       $this->vc19_i_usuario = ($this->vc19_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_i_usuario"]:$this->vc19_i_usuario);
       if($this->vc19_d_data == ""){
         $this->vc19_d_data_dia = ($this->vc19_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_d_data_dia"]:$this->vc19_d_data_dia);
         $this->vc19_d_data_mes = ($this->vc19_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_d_data_mes"]:$this->vc19_d_data_mes);
         $this->vc19_d_data_ano = ($this->vc19_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_d_data_ano"]:$this->vc19_d_data_ano);
         if($this->vc19_d_data_dia != ""){
            $this->vc19_d_data = $this->vc19_d_data_ano."-".$this->vc19_d_data_mes."-".$this->vc19_d_data_dia;
         }
       }
       $this->vc19_c_hora = ($this->vc19_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_c_hora"]:$this->vc19_c_hora);
       $this->vc19_i_matetoqueitemlote = ($this->vc19_i_matetoqueitemlote == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_i_matetoqueitemlote"]:$this->vc19_i_matetoqueitemlote);
       $this->vc19_i_sala = ($this->vc19_i_sala == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_i_sala"]:$this->vc19_i_sala);
     }else{
       $this->vc19_i_codigo = ($this->vc19_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc19_i_codigo"]:$this->vc19_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc19_i_codigo){ 
      $this->atualizacampos();
     if($this->vc19_i_vacina == null ){ 
       $this->erro_sql = " Campo Vacina nao Informado.";
       $this->erro_campo = "vc19_i_vacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_n_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "vc19_n_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_t_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "vc19_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "vc19_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "vc19_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "vc19_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_i_matetoqueitemlote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "vc19_i_matetoqueitemlote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc19_i_sala == null ){ 
       $this->erro_sql = " Campo Sala nao Informado.";
       $this->erro_campo = "vc19_i_sala";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc19_i_codigo == "" || $vc19_i_codigo == null ){
       $result = db_query("select nextval('vac_descarte_vc19_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_descarte_vc19_i_codigo_seq do campo: vc19_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc19_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_descarte_vc19_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc19_i_codigo)){
         $this->erro_sql = " Campo vc19_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc19_i_codigo = $vc19_i_codigo; 
       }
     }
     if(($this->vc19_i_codigo == null) || ($this->vc19_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc19_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_descarte(
                                       vc19_i_codigo 
                                      ,vc19_i_vacina 
                                      ,vc19_n_quant 
                                      ,vc19_t_obs 
                                      ,vc19_i_usuario 
                                      ,vc19_d_data 
                                      ,vc19_c_hora 
                                      ,vc19_i_matetoqueitemlote 
                                      ,vc19_i_sala 
                       )
                values (
                                $this->vc19_i_codigo 
                               ,$this->vc19_i_vacina 
                               ,$this->vc19_n_quant 
                               ,'$this->vc19_t_obs' 
                               ,$this->vc19_i_usuario 
                               ,".($this->vc19_d_data == "null" || $this->vc19_d_data == ""?"null":"'".$this->vc19_d_data."'")." 
                               ,'$this->vc19_c_hora' 
                               ,$this->vc19_i_matetoqueitemlote 
                               ,$this->vc19_i_sala 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Descarte ($this->vc19_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Descarte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Descarte ($this->vc19_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc19_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc19_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16892,'$this->vc19_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2973,16892,'','".AddSlashes(pg_result($resaco,0,'vc19_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,16894,'','".AddSlashes(pg_result($resaco,0,'vc19_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,16898,'','".AddSlashes(pg_result($resaco,0,'vc19_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,16899,'','".AddSlashes(pg_result($resaco,0,'vc19_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,16893,'','".AddSlashes(pg_result($resaco,0,'vc19_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,16896,'','".AddSlashes(pg_result($resaco,0,'vc19_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,16897,'','".AddSlashes(pg_result($resaco,0,'vc19_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,17506,'','".AddSlashes(pg_result($resaco,0,'vc19_i_matetoqueitemlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2973,17557,'','".AddSlashes(pg_result($resaco,0,'vc19_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc19_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_descarte set ";
     $virgula = "";
     if(trim($this->vc19_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_codigo"])){ 
       $sql  .= $virgula." vc19_i_codigo = $this->vc19_i_codigo ";
       $virgula = ",";
       if(trim($this->vc19_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc19_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_i_vacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_vacina"])){ 
       $sql  .= $virgula." vc19_i_vacina = $this->vc19_i_vacina ";
       $virgula = ",";
       if(trim($this->vc19_i_vacina) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "vc19_i_vacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_n_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_n_quant"])){ 
       $sql  .= $virgula." vc19_n_quant = $this->vc19_n_quant ";
       $virgula = ",";
       if(trim($this->vc19_n_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "vc19_n_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_t_obs"])){ 
       $sql  .= $virgula." vc19_t_obs = '$this->vc19_t_obs' ";
       $virgula = ",";
       if(trim($this->vc19_t_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "vc19_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_usuario"])){ 
       $sql  .= $virgula." vc19_i_usuario = $this->vc19_i_usuario ";
       $virgula = ",";
       if(trim($this->vc19_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "vc19_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc19_d_data_dia"] !="") ){ 
       $sql  .= $virgula." vc19_d_data = '$this->vc19_d_data' ";
       $virgula = ",";
       if(trim($this->vc19_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "vc19_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_d_data_dia"])){ 
         $sql  .= $virgula." vc19_d_data = null ";
         $virgula = ",";
         if(trim($this->vc19_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "vc19_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->vc19_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_c_hora"])){ 
       $sql  .= $virgula." vc19_c_hora = '$this->vc19_c_hora' ";
       $virgula = ",";
       if(trim($this->vc19_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "vc19_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_i_matetoqueitemlote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_matetoqueitemlote"])){ 
       $sql  .= $virgula." vc19_i_matetoqueitemlote = $this->vc19_i_matetoqueitemlote ";
       $virgula = ",";
       if(trim($this->vc19_i_matetoqueitemlote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "vc19_i_matetoqueitemlote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc19_i_sala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_sala"])){ 
       $sql  .= $virgula." vc19_i_sala = $this->vc19_i_sala ";
       $virgula = ",";
       if(trim($this->vc19_i_sala) == null ){ 
         $this->erro_sql = " Campo Sala nao Informado.";
         $this->erro_campo = "vc19_i_sala";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc19_i_codigo!=null){
       $sql .= " vc19_i_codigo = $this->vc19_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc19_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16892,'$this->vc19_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_codigo"]) || $this->vc19_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2973,16892,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_i_codigo'))."','$this->vc19_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_vacina"]) || $this->vc19_i_vacina != "")
           $resac = db_query("insert into db_acount values($acount,2973,16894,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_i_vacina'))."','$this->vc19_i_vacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_n_quant"]) || $this->vc19_n_quant != "")
           $resac = db_query("insert into db_acount values($acount,2973,16898,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_n_quant'))."','$this->vc19_n_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_t_obs"]) || $this->vc19_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2973,16899,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_t_obs'))."','$this->vc19_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_usuario"]) || $this->vc19_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2973,16893,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_i_usuario'))."','$this->vc19_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_d_data"]) || $this->vc19_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2973,16896,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_d_data'))."','$this->vc19_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_c_hora"]) || $this->vc19_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2973,16897,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_c_hora'))."','$this->vc19_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_matetoqueitemlote"]) || $this->vc19_i_matetoqueitemlote != "")
           $resac = db_query("insert into db_acount values($acount,2973,17506,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_i_matetoqueitemlote'))."','$this->vc19_i_matetoqueitemlote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc19_i_sala"]) || $this->vc19_i_sala != "")
           $resac = db_query("insert into db_acount values($acount,2973,17557,'".AddSlashes(pg_result($resaco,$conresaco,'vc19_i_sala'))."','$this->vc19_i_sala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descarte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc19_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descarte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc19_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc19_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16892,'$vc19_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2973,16892,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,16894,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,16898,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,16899,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,16893,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,16896,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,16897,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,17506,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_i_matetoqueitemlote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2973,17557,'','".AddSlashes(pg_result($resaco,$iresaco,'vc19_i_sala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_descarte
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc19_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc19_i_codigo = $vc19_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descarte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc19_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descarte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc19_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc19_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_descarte";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_descarte ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = vac_descarte.vc19_i_usuario";
     $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_descarte.vc19_i_matetoqueitemlote";
     $sql .= "      inner join vac_sala  on  vac_sala.vc01_i_codigo = vac_descarte.vc19_i_sala";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_descarte.vc19_i_vacina";
     $sql .= "      inner join matestoqueitem  as a on   a.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = vac_sala.vc01_i_unidade";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = vac_vacina.vc06_i_vacina";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc19_i_codigo!=null ){
         $sql2 .= " where vac_descarte.vc19_i_codigo = $vc19_i_codigo "; 
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
   function sql_query_file ( $vc19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_descarte ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc19_i_codigo!=null ){
         $sql2 .= " where vac_descarte.vc19_i_codigo = $vc19_i_codigo "; 
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
  function sql_query2 ( $vc19_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_descarte ";
     $sql .= "      inner join vac_sala            on  vac_sala.vc01_i_codigo = vac_descarte.vc19_i_sala";
     $sql .= "      inner join db_usuarios         on  db_usuarios.id_usuario = vac_descarte.vc19_i_usuario";
     $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_descarte.vc19_i_matetoqueitemlote";
     $sql .= "      inner join vac_vacina          on  vac_vacina.vc06_i_codigo = vac_descarte.vc19_i_vacina";
     $sql .= "      inner join matestoqueitem      on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join matestoque          on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque ";
     $sql .= "      inner join vac_vacinamaterial  on  vc29_i_vacina = vac_vacina.vc06_i_codigo ";
     $sql .= "                                    and  vc29_i_material = matestoque.m70_codmatmater";
     $sql .= "      inner join matmater            on  matmater.m60_codmater = vac_vacinamaterial.vc29_i_material";
     $sql .= "      inner join vac_tipovacina      on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc19_i_codigo!=null ){
         $sql2 .= " where vac_descarte.vc19_i_codigo = $vc19_i_codigo "; 
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