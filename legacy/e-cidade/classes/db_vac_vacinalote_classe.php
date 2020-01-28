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

//MODULO: Vacinas
//CLASSE DA ENTIDADE vac_vacinalote
class cl_vac_vacinalote { 
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
   var $vc15_i_codigo = 0; 
   var $vc15_i_lote = 0; 
   var $vc15_i_vacina = 0; 
   var $vc15_n_quant = 0; 
   var $vc15_i_logim = 0; 
   var $vc15_c_hora = null; 
   var $vc15_d_data_dia = null; 
   var $vc15_d_data_mes = null; 
   var $vc15_d_data_ano = null; 
   var $vc15_d_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc15_i_codigo = int4 = Código 
                 vc15_i_lote = int4 = Lote 
                 vc15_i_vacina = int4 = Vacina 
                 vc15_n_quant = float4 = Quantidade 
                 vc15_i_logim = int4 = Usuário 
                 vc15_c_hora = char(5) = Hora 
                 vc15_d_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_vac_vacinalote() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_vacinalote"); 
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
       $this->vc15_i_codigo = ($this->vc15_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_i_codigo"]:$this->vc15_i_codigo);
       $this->vc15_i_lote = ($this->vc15_i_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_i_lote"]:$this->vc15_i_lote);
       $this->vc15_i_vacina = ($this->vc15_i_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_i_vacina"]:$this->vc15_i_vacina);
       $this->vc15_n_quant = ($this->vc15_n_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_n_quant"]:$this->vc15_n_quant);
       $this->vc15_i_logim = ($this->vc15_i_logim == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_i_logim"]:$this->vc15_i_logim);
       $this->vc15_c_hora = ($this->vc15_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_c_hora"]:$this->vc15_c_hora);
       if($this->vc15_d_data == ""){
         $this->vc15_d_data_dia = ($this->vc15_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_d_data_dia"]:$this->vc15_d_data_dia);
         $this->vc15_d_data_mes = ($this->vc15_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_d_data_mes"]:$this->vc15_d_data_mes);
         $this->vc15_d_data_ano = ($this->vc15_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_d_data_ano"]:$this->vc15_d_data_ano);
         if($this->vc15_d_data_dia != ""){
            $this->vc15_d_data = $this->vc15_d_data_ano."-".$this->vc15_d_data_mes."-".$this->vc15_d_data_dia;
         }
       }
     }else{
       $this->vc15_i_codigo = ($this->vc15_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc15_i_codigo"]:$this->vc15_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc15_i_codigo){ 
      $this->atualizacampos();
     if($this->vc15_i_lote == null ){ 
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "vc15_i_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc15_i_vacina == null ){ 
       $this->erro_sql = " Campo Vacina nao Informado.";
       $this->erro_campo = "vc15_i_vacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc15_n_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "vc15_n_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc15_i_logim == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "vc15_i_logim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc15_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "vc15_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc15_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "vc15_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc15_i_codigo == "" || $vc15_i_codigo == null ){
       $result = db_query("select nextval('vac_vacinalote_vc15_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_vacinalote_vc15_i_codigo_seq do campo: vc15_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc15_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_vacinalote_vc15_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc15_i_codigo)){
         $this->erro_sql = " Campo vc15_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc15_i_codigo = $vc15_i_codigo; 
       }
     }
     if(($this->vc15_i_codigo == null) || ($this->vc15_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc15_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_vacinalote(
                                       vc15_i_codigo 
                                      ,vc15_i_lote 
                                      ,vc15_i_vacina 
                                      ,vc15_n_quant 
                                      ,vc15_i_logim 
                                      ,vc15_c_hora 
                                      ,vc15_d_data 
                       )
                values (
                                $this->vc15_i_codigo 
                               ,$this->vc15_i_lote 
                               ,$this->vc15_i_vacina 
                               ,$this->vc15_n_quant 
                               ,$this->vc15_i_logim 
                               ,'$this->vc15_c_hora' 
                               ,".($this->vc15_d_data == "null" || $this->vc15_d_data == ""?"null":"'".$this->vc15_d_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vaina lotes ($this->vc15_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vaina lotes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vaina lotes ($this->vc15_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc15_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc15_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16867,'$this->vc15_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2969,16867,'','".AddSlashes(pg_result($resaco,0,'vc15_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2969,16868,'','".AddSlashes(pg_result($resaco,0,'vc15_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2969,16869,'','".AddSlashes(pg_result($resaco,0,'vc15_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2969,16870,'','".AddSlashes(pg_result($resaco,0,'vc15_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2969,16871,'','".AddSlashes(pg_result($resaco,0,'vc15_i_logim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2969,16873,'','".AddSlashes(pg_result($resaco,0,'vc15_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2969,16872,'','".AddSlashes(pg_result($resaco,0,'vc15_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc15_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_vacinalote set ";
     $virgula = "";
     if(trim($this->vc15_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_codigo"])){ 
       $sql  .= $virgula." vc15_i_codigo = $this->vc15_i_codigo ";
       $virgula = ",";
       if(trim($this->vc15_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc15_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc15_i_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_lote"])){ 
       $sql  .= $virgula." vc15_i_lote = $this->vc15_i_lote ";
       $virgula = ",";
       if(trim($this->vc15_i_lote) == null ){ 
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "vc15_i_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc15_i_vacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_vacina"])){ 
       $sql  .= $virgula." vc15_i_vacina = $this->vc15_i_vacina ";
       $virgula = ",";
       if(trim($this->vc15_i_vacina) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "vc15_i_vacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc15_n_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_n_quant"])){ 
       $sql  .= $virgula." vc15_n_quant = $this->vc15_n_quant ";
       $virgula = ",";
       if(trim($this->vc15_n_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "vc15_n_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc15_i_logim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_logim"])){ 
       $sql  .= $virgula." vc15_i_logim = $this->vc15_i_logim ";
       $virgula = ",";
       if(trim($this->vc15_i_logim) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "vc15_i_logim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc15_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_c_hora"])){ 
       $sql  .= $virgula." vc15_c_hora = '$this->vc15_c_hora' ";
       $virgula = ",";
       if(trim($this->vc15_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "vc15_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc15_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc15_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["vc15_d_data_dia"] !="") ){ 
       $sql  .= $virgula." vc15_d_data = '$this->vc15_d_data' ";
       $virgula = ",";
       if(trim($this->vc15_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "vc15_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_d_data_dia"])){ 
         $sql  .= $virgula." vc15_d_data = null ";
         $virgula = ",";
         if(trim($this->vc15_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "vc15_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($vc15_i_codigo!=null){
       $sql .= " vc15_i_codigo = $this->vc15_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc15_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16867,'$this->vc15_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_codigo"]) || $this->vc15_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2969,16867,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_i_codigo'))."','$this->vc15_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_lote"]) || $this->vc15_i_lote != "")
           $resac = db_query("insert into db_acount values($acount,2969,16868,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_i_lote'))."','$this->vc15_i_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_vacina"]) || $this->vc15_i_vacina != "")
           $resac = db_query("insert into db_acount values($acount,2969,16869,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_i_vacina'))."','$this->vc15_i_vacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_n_quant"]) || $this->vc15_n_quant != "")
           $resac = db_query("insert into db_acount values($acount,2969,16870,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_n_quant'))."','$this->vc15_n_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_i_logim"]) || $this->vc15_i_logim != "")
           $resac = db_query("insert into db_acount values($acount,2969,16871,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_i_logim'))."','$this->vc15_i_logim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_c_hora"]) || $this->vc15_c_hora != "")
           $resac = db_query("insert into db_acount values($acount,2969,16873,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_c_hora'))."','$this->vc15_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc15_d_data"]) || $this->vc15_d_data != "")
           $resac = db_query("insert into db_acount values($acount,2969,16872,'".AddSlashes(pg_result($resaco,$conresaco,'vc15_d_data'))."','$this->vc15_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vaina lotes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc15_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vaina lotes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc15_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc15_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16867,'$vc15_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2969,16867,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2969,16868,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2969,16869,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2969,16870,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2969,16871,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_i_logim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2969,16873,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2969,16872,'','".AddSlashes(pg_result($resaco,$iresaco,'vc15_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_vacinalote
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc15_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc15_i_codigo = $vc15_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vaina lotes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc15_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vaina lotes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc15_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc15_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_vacinalote";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinalote ";
     $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_vacinalote.vc15_i_lote";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_vacinalote.vc15_i_vacina";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = vac_vacina.vc06_i_vacina";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc15_i_codigo!=null ){
         $sql2 .= " where vac_vacinalote.vc15_i_codigo = $vc15_i_codigo "; 
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
  function sql_query_matestoque ( $vc15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinalote ";
     $sql .= "      inner join matestoqueitemlote  on  matestoqueitemlote.m77_sequencial = vac_vacinalote.vc15_i_lote";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_vacinalote.vc15_i_vacina";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
     $sql .= "      inner join matestoque      on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = vac_vacina.vc06_i_vacina";
     $sql .= "      inner join matmaterunisai  on  matmaterunisai.m62_codmater = matmater.m60_codmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmaterunisai.m62_codmatunid";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc15_i_codigo!=null ){
         $sql2 .= " where vac_vacinalote.vc15_i_codigo = $vc15_i_codigo "; 
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
   function sql_query_file ( $vc15_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacinalote ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc15_i_codigo!=null ){
         $sql2 .= " where vac_vacinalote.vc15_i_codigo = $vc15_i_codigo "; 
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