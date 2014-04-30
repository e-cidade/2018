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
//CLASSE DA ENTIDADE vac_boletim
class cl_vac_boletim { 
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
   var $vc13_i_codigo = 0; 
   var $vc13_i_vacina = 0; 
   var $vc13_c_descr = null; 
   var $vc13_i_diaini = 0; 
   var $vc13_i_mesini = 0; 
   var $vc13_i_anoini = 0; 
   var $vc13_i_diafim = 0; 
   var $vc13_i_mesfim = 0; 
   var $vc13_i_anofim = 0; 
   var $vc13_i_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 vc13_i_codigo = int4 = Código 
                 vc13_i_vacina = int4 = Vacina 
                 vc13_c_descr = char(30) = Descrição 
                 vc13_i_diaini = int4 = Dia inicial 
                 vc13_i_mesini = int4 = Mês inicial 
                 vc13_i_anoini = int4 = Ano inicial 
                 vc13_i_diafim = int4 = Dia final 
                 vc13_i_mesfim = int4 = Mês final 
                 vc13_i_anofim = int4 = Ano final 
                 vc13_i_situacao = int4 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_vac_boletim() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vac_boletim"); 
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
       $this->vc13_i_codigo = ($this->vc13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_codigo"]:$this->vc13_i_codigo);
       $this->vc13_i_vacina = ($this->vc13_i_vacina == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_vacina"]:$this->vc13_i_vacina);
       $this->vc13_c_descr = ($this->vc13_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_c_descr"]:$this->vc13_c_descr);
       $this->vc13_i_diaini = ($this->vc13_i_diaini == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_diaini"]:$this->vc13_i_diaini);
       $this->vc13_i_mesini = ($this->vc13_i_mesini == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_mesini"]:$this->vc13_i_mesini);
       $this->vc13_i_anoini = ($this->vc13_i_anoini == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_anoini"]:$this->vc13_i_anoini);
       $this->vc13_i_diafim = ($this->vc13_i_diafim == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_diafim"]:$this->vc13_i_diafim);
       $this->vc13_i_mesfim = ($this->vc13_i_mesfim == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_mesfim"]:$this->vc13_i_mesfim);
       $this->vc13_i_anofim = ($this->vc13_i_anofim == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_anofim"]:$this->vc13_i_anofim);
       $this->vc13_i_situacao = ($this->vc13_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_situacao"]:$this->vc13_i_situacao);
     }else{
       $this->vc13_i_codigo = ($this->vc13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["vc13_i_codigo"]:$this->vc13_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($vc13_i_codigo){ 
      $this->atualizacampos();
     if($this->vc13_i_vacina == null ){ 
       $this->erro_sql = " Campo Vacina nao Informado.";
       $this->erro_campo = "vc13_i_vacina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "vc13_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_i_diaini == null ){ 
       $this->erro_sql = " Campo Dia inicial nao Informado.";
       $this->erro_campo = "vc13_i_diaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_i_mesini == null ){ 
       $this->erro_sql = " Campo Mês inicial nao Informado.";
       $this->erro_campo = "vc13_i_mesini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_i_anoini == null ){ 
       $this->erro_sql = " Campo Ano inicial nao Informado.";
       $this->erro_campo = "vc13_i_anoini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_i_diafim == null ){ 
       $this->vc13_i_diafim = "0";
     }
     if($this->vc13_i_mesfim == null ){ 
       $this->erro_sql = " Campo Mês final nao Informado.";
       $this->erro_campo = "vc13_i_mesfim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_i_anofim == null ){ 
       $this->erro_sql = " Campo Ano final nao Informado.";
       $this->erro_campo = "vc13_i_anofim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->vc13_i_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "vc13_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($vc13_i_codigo == "" || $vc13_i_codigo == null ){
       $result = db_query("select nextval('vac_boletim_vc13_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vac_boletim_vc13_i_codigo_seq do campo: vc13_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->vc13_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vac_boletim_vc13_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $vc13_i_codigo)){
         $this->erro_sql = " Campo vc13_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->vc13_i_codigo = $vc13_i_codigo; 
       }
     }
     if(($this->vc13_i_codigo == null) || ($this->vc13_i_codigo == "") ){ 
       $this->erro_sql = " Campo vc13_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vac_boletim(
                                       vc13_i_codigo 
                                      ,vc13_i_vacina 
                                      ,vc13_c_descr 
                                      ,vc13_i_diaini 
                                      ,vc13_i_mesini 
                                      ,vc13_i_anoini 
                                      ,vc13_i_diafim 
                                      ,vc13_i_mesfim 
                                      ,vc13_i_anofim 
                                      ,vc13_i_situacao 
                       )
                values (
                                $this->vc13_i_codigo 
                               ,$this->vc13_i_vacina 
                               ,'$this->vc13_c_descr' 
                               ,$this->vc13_i_diaini 
                               ,$this->vc13_i_mesini 
                               ,$this->vc13_i_anoini 
                               ,$this->vc13_i_diafim 
                               ,$this->vc13_i_mesfim 
                               ,$this->vc13_i_anofim 
                               ,$this->vc13_i_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Boletim ($this->vc13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Boletim já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Boletim ($this->vc13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc13_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->vc13_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16852,'$this->vc13_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2967,16852,'','".AddSlashes(pg_result($resaco,0,'vc13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16853,'','".AddSlashes(pg_result($resaco,0,'vc13_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16855,'','".AddSlashes(pg_result($resaco,0,'vc13_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16856,'','".AddSlashes(pg_result($resaco,0,'vc13_i_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16857,'','".AddSlashes(pg_result($resaco,0,'vc13_i_mesini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16858,'','".AddSlashes(pg_result($resaco,0,'vc13_i_anoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16859,'','".AddSlashes(pg_result($resaco,0,'vc13_i_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16860,'','".AddSlashes(pg_result($resaco,0,'vc13_i_mesfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16861,'','".AddSlashes(pg_result($resaco,0,'vc13_i_anofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2967,16854,'','".AddSlashes(pg_result($resaco,0,'vc13_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($vc13_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vac_boletim set ";
     $virgula = "";
     if(trim($this->vc13_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_codigo"])){ 
       $sql  .= $virgula." vc13_i_codigo = $this->vc13_i_codigo ";
       $virgula = ",";
       if(trim($this->vc13_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "vc13_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_vacina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_vacina"])){ 
       $sql  .= $virgula." vc13_i_vacina = $this->vc13_i_vacina ";
       $virgula = ",";
       if(trim($this->vc13_i_vacina) == null ){ 
         $this->erro_sql = " Campo Vacina nao Informado.";
         $this->erro_campo = "vc13_i_vacina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_c_descr"])){ 
       $sql  .= $virgula." vc13_c_descr = '$this->vc13_c_descr' ";
       $virgula = ",";
       if(trim($this->vc13_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "vc13_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_diaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_diaini"])){ 
       $sql  .= $virgula." vc13_i_diaini = $this->vc13_i_diaini ";
       $virgula = ",";
       if(trim($this->vc13_i_diaini) == null ){ 
         $this->erro_sql = " Campo Dia inicial nao Informado.";
         $this->erro_campo = "vc13_i_diaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_mesini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_mesini"])){ 
       $sql  .= $virgula." vc13_i_mesini = $this->vc13_i_mesini ";
       $virgula = ",";
       if(trim($this->vc13_i_mesini) == null ){ 
         $this->erro_sql = " Campo Mês inicial nao Informado.";
         $this->erro_campo = "vc13_i_mesini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_anoini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_anoini"])){ 
       $sql  .= $virgula." vc13_i_anoini = $this->vc13_i_anoini ";
       $virgula = ",";
       if(trim($this->vc13_i_anoini) == null ){ 
         $this->erro_sql = " Campo Ano inicial nao Informado.";
         $this->erro_campo = "vc13_i_anoini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_diafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_diafim"])){ 
        if(trim($this->vc13_i_diafim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_diafim"])){ 
           $this->vc13_i_diafim = "0" ; 
        } 
       $sql  .= $virgula." vc13_i_diafim = $this->vc13_i_diafim ";
       $virgula = ",";
     }
     if(trim($this->vc13_i_mesfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_mesfim"])){ 
       $sql  .= $virgula." vc13_i_mesfim = $this->vc13_i_mesfim ";
       $virgula = ",";
       if(trim($this->vc13_i_mesfim) == null ){ 
         $this->erro_sql = " Campo Mês final nao Informado.";
         $this->erro_campo = "vc13_i_mesfim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_anofim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_anofim"])){ 
       $sql  .= $virgula." vc13_i_anofim = $this->vc13_i_anofim ";
       $virgula = ",";
       if(trim($this->vc13_i_anofim) == null ){ 
         $this->erro_sql = " Campo Ano final nao Informado.";
         $this->erro_campo = "vc13_i_anofim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->vc13_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_situacao"])){ 
       $sql  .= $virgula." vc13_i_situacao = $this->vc13_i_situacao ";
       $virgula = ",";
       if(trim($this->vc13_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "vc13_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($vc13_i_codigo!=null){
       $sql .= " vc13_i_codigo = $this->vc13_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->vc13_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16852,'$this->vc13_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_codigo"]) || $this->vc13_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2967,16852,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_codigo'))."','$this->vc13_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_vacina"]) || $this->vc13_i_vacina != "")
           $resac = db_query("insert into db_acount values($acount,2967,16853,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_vacina'))."','$this->vc13_i_vacina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_c_descr"]) || $this->vc13_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2967,16855,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_c_descr'))."','$this->vc13_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_diaini"]) || $this->vc13_i_diaini != "")
           $resac = db_query("insert into db_acount values($acount,2967,16856,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_diaini'))."','$this->vc13_i_diaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_mesini"]) || $this->vc13_i_mesini != "")
           $resac = db_query("insert into db_acount values($acount,2967,16857,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_mesini'))."','$this->vc13_i_mesini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_anoini"]) || $this->vc13_i_anoini != "")
           $resac = db_query("insert into db_acount values($acount,2967,16858,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_anoini'))."','$this->vc13_i_anoini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_diafim"]) || $this->vc13_i_diafim != "")
           $resac = db_query("insert into db_acount values($acount,2967,16859,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_diafim'))."','$this->vc13_i_diafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_mesfim"]) || $this->vc13_i_mesfim != "")
           $resac = db_query("insert into db_acount values($acount,2967,16860,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_mesfim'))."','$this->vc13_i_mesfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_anofim"]) || $this->vc13_i_anofim != "")
           $resac = db_query("insert into db_acount values($acount,2967,16861,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_anofim'))."','$this->vc13_i_anofim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["vc13_i_situacao"]) || $this->vc13_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2967,16854,'".AddSlashes(pg_result($resaco,$conresaco,'vc13_i_situacao'))."','$this->vc13_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boletim nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boletim nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->vc13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->vc13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($vc13_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($vc13_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16852,'$vc13_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2967,16852,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16853,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_vacina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16855,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16856,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16857,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_mesini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16858,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_anoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16859,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16860,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_mesfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16861,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_anofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2967,16854,'','".AddSlashes(pg_result($resaco,$iresaco,'vc13_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vac_boletim
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($vc13_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " vc13_i_codigo = $vc13_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boletim nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$vc13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boletim nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$vc13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$vc13_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vac_boletim";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $vc13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_boletim ";
     $sql .= "      inner join vac_vacina  on  vac_vacina.vc06_i_codigo = vac_boletim.vc13_i_vacina";
     $sql .= "      inner join vac_tipovacina  on  vac_tipovacina.vc04_i_codigo = vac_vacina.vc06_i_tipovacina";
     $sql2 = "";
     if($dbwhere==""){
       if($vc13_i_codigo!=null ){
         $sql2 .= " where vac_boletim.vc13_i_codigo = $vc13_i_codigo "; 
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
   function sql_query_file ( $vc13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vac_boletim ";
     $sql2 = "";
     if($dbwhere==""){
       if($vc13_i_codigo!=null ){
         $sql2 .= " where vac_boletim.vc13_i_codigo = $vc13_i_codigo "; 
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