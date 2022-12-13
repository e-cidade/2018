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
//CLASSE DA ENTIDADE vac_vacina
class cl_vac_vacina { 
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
   var $vc06_i_codigo = 0; 
   var $vc06_c_descr = null; 
   var $vc06_i_basico = 0; 
   var $vc06_t_administacao = null; 
   var $vc06_c_codpni = null; 
   var $vc06_i_tipovacina = 0; 
   var $vc06_i_situacao = 0; 
   var $vc06_i_orden = 0; 
   var $vc06_t_material = null; 
   var $vc06_n_quant = 0; 
   var $vc06_t_obs = null; 
   var $vc06_i_tipo = 0; 
   var $vc06_c_prazo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc06_i_codigo = int4 = Código 
                 vc06_c_descr = char(100) = Descrição 
                 vc06_i_basico = int4 = Básico 
                 vc06_t_administacao = text = Administração 
                 vc06_c_codpni = char(10) = Código PNI 
                 vc06_i_tipovacina = int4 = Tipo de vacina 
                 vc06_i_situacao = int4 = Situação 
                 vc06_i_orden = int4 = Ordem 
                 vc06_t_material = text = Material 
                 vc06_n_quant = float4 = Quantidade 
                 vc06_t_obs = text = Observação 
                 vc06_i_tipo = int4 = Tipo 
                 vc06_c_prazo = char(20) = Prazo 
                 ";
   //funcao construtor da classe 
   function cl_vac_vacina() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_vacina"); 
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
       $this->vc06_i_codigo = ($this->vc06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_codigo"]:$this->vc06_i_codigo);
       $this->vc06_c_descr = ($this->vc06_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_c_descr"]:$this->vc06_c_descr);
       $this->vc06_i_basico = ($this->vc06_i_basico == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_basico"]:$this->vc06_i_basico);
       $this->vc06_t_administacao = ($this->vc06_t_administacao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_t_administacao"]:$this->vc06_t_administacao);
       $this->vc06_c_codpni = ($this->vc06_c_codpni == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_c_codpni"]:$this->vc06_c_codpni);
       $this->vc06_i_tipovacina = ($this->vc06_i_tipovacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_tipovacina"]:$this->vc06_i_tipovacina);
       $this->vc06_i_situacao = ($this->vc06_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_situacao"]:$this->vc06_i_situacao);
       $this->vc06_i_orden = ($this->vc06_i_orden == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_orden"]:$this->vc06_i_orden);
       $this->vc06_t_material = ($this->vc06_t_material == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_t_material"]:$this->vc06_t_material);
       $this->vc06_n_quant = ($this->vc06_n_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_n_quant"]:$this->vc06_n_quant);
       $this->vc06_t_obs = ($this->vc06_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_t_obs"]:$this->vc06_t_obs);
       $this->vc06_i_tipo = ($this->vc06_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_tipo"]:$this->vc06_i_tipo);
       $this->vc06_c_prazo = ($this->vc06_c_prazo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_c_prazo"]:$this->vc06_c_prazo);
     }else{
       $this->vc06_i_codigo = ($this->vc06_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc06_i_codigo"]:$this->vc06_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc06_i_codigo){ 
      $this->atualizacampos();
     if($this->vc06_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "vc06_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc06_i_basico == null ){ 
       $this->erro_sql = " Campo Básico nao Informado.";
       $this->erro_campo = "vc06_i_basico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc06_c_codpni == null ){ 
       $this->erro_sql = " Campo Código PNI nao Informado.";
       $this->erro_campo = "vc06_c_codpni";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc06_i_tipovacina == null ){ 
       $this->erro_sql = " Campo Tipo de vacina nao Informado.";
       $this->erro_campo = "vc06_i_tipovacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc06_i_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "vc06_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc06_i_orden == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "vc06_i_orden";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc06_n_quant == null ){ 
       $this->vc06_n_quant = "0";
     }
     if($this->vc06_i_tipo == null ){ 
       $this->vc06_i_tipo = "0";
     }
     if($vc06_i_codigo == "" || $vc06_i_codigo == null ){
       $result = db_query("select nextval('vac_vacina_vc06_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_vacina_vc06_i_codigo_seq do campo: vc06_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc06_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_vacina_vc06_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc06_i_codigo)){
         $this->erro_sql = " Campo vc06_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc06_i_codigo = $vc06_i_codigo; 
       }
     }
     if(($this->vc06_i_codigo == null) || ($this->vc06_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc06_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_vacina(
                                       vc06_i_codigo 
                                      ,vc06_c_descr 
                                      ,vc06_i_basico 
                                      ,vc06_t_administacao 
                                      ,vc06_c_codpni 
                                      ,vc06_i_tipovacina 
                                      ,vc06_i_situacao 
                                      ,vc06_i_orden 
                                      ,vc06_t_material 
                                      ,vc06_n_quant 
                                      ,vc06_t_obs 
                                      ,vc06_i_tipo 
                                      ,vc06_c_prazo 
                       )
                values (
                                $this->vc06_i_codigo 
                               ,'$this->vc06_c_descr' 
                               ,$this->vc06_i_basico 
                               ,'$this->vc06_t_administacao' 
                               ,'$this->vc06_c_codpni' 
                               ,$this->vc06_i_tipovacina 
                               ,$this->vc06_i_situacao 
                               ,$this->vc06_i_orden 
                               ,'$this->vc06_t_material' 
                               ,$this->vc06_n_quant 
                               ,'$this->vc06_t_obs' 
                               ,$this->vc06_i_tipo 
                               ,'$this->vc06_c_prazo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Vacinas ($this->vc06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Vacinas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Vacinas ($this->vc06_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc06_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc06_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16802,'$this->vc06_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2960,16802,'','".AddSlashes(pg_result($resaco,0,'vc06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16804,'','".AddSlashes(pg_result($resaco,0,'vc06_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16807,'','".AddSlashes(pg_result($resaco,0,'vc06_i_basico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16810,'','".AddSlashes(pg_result($resaco,0,'vc06_t_administacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16805,'','".AddSlashes(pg_result($resaco,0,'vc06_c_codpni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16806,'','".AddSlashes(pg_result($resaco,0,'vc06_i_tipovacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16808,'','".AddSlashes(pg_result($resaco,0,'vc06_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16809,'','".AddSlashes(pg_result($resaco,0,'vc06_i_orden'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16811,'','".AddSlashes(pg_result($resaco,0,'vc06_t_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16812,'','".AddSlashes(pg_result($resaco,0,'vc06_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16813,'','".AddSlashes(pg_result($resaco,0,'vc06_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,16825,'','".AddSlashes(pg_result($resaco,0,'vc06_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2960,17072,'','".AddSlashes(pg_result($resaco,0,'vc06_c_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc06_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_vacina set ";
     $virgula = "";
     if(trim($this->vc06_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_codigo"])){ 
       $sql  .= $virgula." vc06_i_codigo = $this->vc06_i_codigo ";
       $virgula = ",";
       if(trim($this->vc06_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc06_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_c_descr"])){ 
       $sql  .= $virgula." vc06_c_descr = '$this->vc06_c_descr' ";
       $virgula = ",";
       if(trim($this->vc06_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "vc06_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_i_basico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_basico"])){ 
       $sql  .= $virgula." vc06_i_basico = $this->vc06_i_basico ";
       $virgula = ",";
       if(trim($this->vc06_i_basico) == null ){ 
         $this->erro_sql = " Campo Básico nao Informado.";
         $this->erro_campo = "vc06_i_basico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_t_administacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_t_administacao"])){ 
       $sql  .= $virgula." vc06_t_administacao = '$this->vc06_t_administacao' ";
       $virgula = ",";
     }
     if(trim($this->vc06_c_codpni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_c_codpni"])){ 
       $sql  .= $virgula." vc06_c_codpni = '$this->vc06_c_codpni' ";
       $virgula = ",";
       if(trim($this->vc06_c_codpni) == null ){ 
         $this->erro_sql = " Campo Código PNI nao Informado.";
         $this->erro_campo = "vc06_c_codpni";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_i_tipovacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_tipovacina"])){ 
       $sql  .= $virgula." vc06_i_tipovacina = $this->vc06_i_tipovacina ";
       $virgula = ",";
       if(trim($this->vc06_i_tipovacina) == null ){ 
         $this->erro_sql = " Campo Tipo de vacina nao Informado.";
         $this->erro_campo = "vc06_i_tipovacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_situacao"])){ 
       $sql  .= $virgula." vc06_i_situacao = $this->vc06_i_situacao ";
       $virgula = ",";
       if(trim($this->vc06_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "vc06_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_i_orden)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_orden"])){ 
       $sql  .= $virgula." vc06_i_orden = $this->vc06_i_orden ";
       $virgula = ",";
       if(trim($this->vc06_i_orden) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "vc06_i_orden";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc06_t_material)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_t_material"])){ 
       $sql  .= $virgula." vc06_t_material = '$this->vc06_t_material' ";
       $virgula = ",";
     }
     if(trim($this->vc06_n_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_n_quant"])){ 
        if(trim($this->vc06_n_quant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc06_n_quant"])){ 
           $this->vc06_n_quant = "0" ; 
        } 
       $sql  .= $virgula." vc06_n_quant = $this->vc06_n_quant ";
       $virgula = ",";
     }
     if(trim($this->vc06_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_t_obs"])){ 
       $sql  .= $virgula." vc06_t_obs = '$this->vc06_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->vc06_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_tipo"])){ 
        if(trim($this->vc06_i_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_tipo"])){ 
           $this->vc06_i_tipo = "0" ; 
        } 
       $sql  .= $virgula." vc06_i_tipo = $this->vc06_i_tipo ";
       $virgula = ",";
     }
     if(trim($this->vc06_c_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc06_c_prazo"])){ 
       $sql  .= $virgula." vc06_c_prazo = '$this->vc06_c_prazo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($vc06_i_codigo!=null){
       $sql .= " vc06_i_codigo = $this->vc06_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc06_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16802,'$this->vc06_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_codigo"]) || $this->vc06_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2960,16802,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_i_codigo'))."','$this->vc06_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_c_descr"]) || $this->vc06_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2960,16804,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_c_descr'))."','$this->vc06_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_basico"]) || $this->vc06_i_basico != "")
           $resac = db_query("insert into db_acount values($acount,2960,16807,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_i_basico'))."','$this->vc06_i_basico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_t_administacao"]) || $this->vc06_t_administacao != "")
           $resac = db_query("insert into db_acount values($acount,2960,16810,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_t_administacao'))."','$this->vc06_t_administacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_c_codpni"]) || $this->vc06_c_codpni != "")
           $resac = db_query("insert into db_acount values($acount,2960,16805,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_c_codpni'))."','$this->vc06_c_codpni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_tipovacina"]) || $this->vc06_i_tipovacina != "")
           $resac = db_query("insert into db_acount values($acount,2960,16806,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_i_tipovacina'))."','$this->vc06_i_tipovacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_situacao"]) || $this->vc06_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2960,16808,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_i_situacao'))."','$this->vc06_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_orden"]) || $this->vc06_i_orden != "")
           $resac = db_query("insert into db_acount values($acount,2960,16809,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_i_orden'))."','$this->vc06_i_orden',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_t_material"]) || $this->vc06_t_material != "")
           $resac = db_query("insert into db_acount values($acount,2960,16811,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_t_material'))."','$this->vc06_t_material',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_n_quant"]) || $this->vc06_n_quant != "")
           $resac = db_query("insert into db_acount values($acount,2960,16812,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_n_quant'))."','$this->vc06_n_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_t_obs"]) || $this->vc06_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,2960,16813,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_t_obs'))."','$this->vc06_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_i_tipo"]) || $this->vc06_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2960,16825,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_i_tipo'))."','$this->vc06_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc06_c_prazo"]) || $this->vc06_c_prazo != "")
           $resac = db_query("insert into db_acount values($acount,2960,17072,'".AddSlashes(pg_result($resaco,$conresaco,'vc06_c_prazo'))."','$this->vc06_c_prazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Vacinas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Vacinas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc06_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc06_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16802,'$vc06_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2960,16802,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16804,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16807,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_i_basico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16810,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_t_administacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16805,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_c_codpni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16806,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_i_tipovacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16808,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16809,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_i_orden'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16811,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_t_material'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16812,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_n_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16813,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,16825,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2960,17072,'','".AddSlashes(pg_result($resaco,$iresaco,'vc06_c_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_vacina
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc06_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc06_i_codigo = $vc06_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Vacinas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc06_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Vacinas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc06_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc06_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_vacina";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacina ";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc06_i_codigo!=null ){
         $sql2 .= " where vac_vacina.vc06_i_codigo = $vc06_i_codigo "; 
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
   function sql_query_file ( $vc06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_vacina ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc06_i_codigo!=null ){
         $sql2 .= " where vac_vacina.vc06_i_codigo = $vc06_i_codigo "; 
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
   function sql_query2 ( $vc06_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
  $sql .= " from vac_vacina ";
  $sql .= "      inner join matmater  on  matmater.m60_codmater = vac_vacina.vc06_i_vacina";
  $sql .= "      inner join matmaterunisai    on matmaterunisai.m62_codmater         = matmater.m60_codmater ";
  $sql .= "      inner join matunid           on matunid.m61_codmatunid              = matmaterunisai.m62_codmatunid ";
  $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
  $sql2 = "";
  if($dbwhere==""){
    if($vc06_i_codigo!=null ){
      $sql2 .= " where vac_vacina.vc06_i_codigo = $vc06_i_codigo ";
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