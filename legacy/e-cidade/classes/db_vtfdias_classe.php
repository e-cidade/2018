<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE vtfdias
class cl_vtfdias { 
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
   var $r63_anousu = 0; 
   var $r63_mesusu = 0; 
   var $r63_regist = 0; 
   var $r63_vale = null; 
   var $r63_difere = 'f'; 
   var $r63_dia_dia = null; 
   var $r63_dia_mes = null; 
   var $r63_dia_ano = null; 
   var $r63_dia = null; 
   var $r63_quant = 0; 
   var $r63_obrig = 'f'; 
   var $r63_quants = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r63_anousu = int4 = Ano do Exercicio 
                 r63_mesusu = int4 = Mes do Exercicio 
                 r63_regist = int4 = Codigo do Funcionario 
                 r63_vale = char(     4) = CODIGO DO VALE TRANSPORTE 
                 r63_difere = boolean = Se difere cal.r922 senao r916 
                 r63_dia = date = dia do vale transporte 
                 r63_quant = int4 = Qtda de vales do dia 
                 r63_obrig = boolean = informa se e obrigatorio 
                 r63_quants = int4 = qtd vales vinda da semana 
                 ";
   //funcao construtor da classe 
   function cl_vtfdias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vtfdias"); 
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
       $this->r63_anousu = ($this->r63_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_anousu"]:$this->r63_anousu);
       $this->r63_mesusu = ($this->r63_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_mesusu"]:$this->r63_mesusu);
       $this->r63_regist = ($this->r63_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_regist"]:$this->r63_regist);
       $this->r63_vale = ($this->r63_vale == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_vale"]:$this->r63_vale);
       $this->r63_difere = ($this->r63_difere == "f"?@$GLOBALS["HTTP_POST_VARS"]["r63_difere"]:$this->r63_difere);
       if($this->r63_dia == ""){
         $this->r63_dia_dia = ($this->r63_dia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_dia_dia"]:$this->r63_dia_dia);
         $this->r63_dia_mes = ($this->r63_dia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_dia_mes"]:$this->r63_dia_mes);
         $this->r63_dia_ano = ($this->r63_dia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_dia_ano"]:$this->r63_dia_ano);
         if($this->r63_dia_dia != ""){
            $this->r63_dia = $this->r63_dia_ano."-".$this->r63_dia_mes."-".$this->r63_dia_dia;
         }
       }
       $this->r63_quant = ($this->r63_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_quant"]:$this->r63_quant);
       $this->r63_obrig = ($this->r63_obrig == "f"?@$GLOBALS["HTTP_POST_VARS"]["r63_obrig"]:$this->r63_obrig);
       $this->r63_quants = ($this->r63_quants == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_quants"]:$this->r63_quants);
     }else{
       $this->r63_anousu = ($this->r63_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_anousu"]:$this->r63_anousu);
       $this->r63_mesusu = ($this->r63_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_mesusu"]:$this->r63_mesusu);
       $this->r63_regist = ($this->r63_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_regist"]:$this->r63_regist);
       $this->r63_vale = ($this->r63_vale == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_vale"]:$this->r63_vale);
       $this->r63_difere = ($this->r63_difere == "f"?@$GLOBALS["HTTP_POST_VARS"]["r63_difere"]:$this->r63_difere);
       $this->r63_dia = ($this->r63_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r63_dia_ano"]."-".@$GLOBALS["HTTP_POST_VARS"]["r63_dia_mes"]."-".@$GLOBALS["HTTP_POST_VARS"]["r63_dia_dia"]:$this->r63_dia);
     }
   }
   // funcao para inclusao
   function incluir ($r63_anousu,$r63_mesusu,$r63_regist,$r63_vale,$r63_difere,$r63_dia){ 
      $this->atualizacampos();
     if($this->r63_quant == null ){ 
       $this->erro_sql = " Campo Qtda de vales do dia nao Informado.";
       $this->erro_campo = "r63_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r63_obrig == null ){ 
       $this->erro_sql = " Campo informa se e obrigatorio nao Informado.";
       $this->erro_campo = "r63_obrig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r63_quants == null ){ 
       $this->erro_sql = " Campo qtd vales vinda da semana nao Informado.";
       $this->erro_campo = "r63_quants";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r63_anousu = $r63_anousu; 
       $this->r63_mesusu = $r63_mesusu; 
       $this->r63_regist = $r63_regist; 
       $this->r63_vale = $r63_vale; 
       $this->r63_difere = $r63_difere; 
       $this->r63_dia = $r63_dia; 
     if(($this->r63_anousu == null) || ($this->r63_anousu == "") ){ 
       $this->erro_sql = " Campo r63_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r63_mesusu == null) || ($this->r63_mesusu == "") ){ 
       $this->erro_sql = " Campo r63_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r63_regist == null) || ($this->r63_regist == "") ){ 
       $this->erro_sql = " Campo r63_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r63_vale == null) || ($this->r63_vale == "") ){ 
       $this->erro_sql = " Campo r63_vale nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r63_difere == null) || ($this->r63_difere == "") ){ 
       $this->erro_sql = " Campo r63_difere nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r63_dia == null) || ($this->r63_dia == "") ){ 
       $this->erro_sql = " Campo r63_dia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vtfdias(
                                       r63_anousu 
                                      ,r63_mesusu 
                                      ,r63_regist 
                                      ,r63_vale 
                                      ,r63_difere 
                                      ,r63_dia 
                                      ,r63_quant 
                                      ,r63_obrig 
                                      ,r63_quants 
                       )
                values (
                                $this->r63_anousu 
                               ,$this->r63_mesusu 
                               ,$this->r63_regist 
                               ,'$this->r63_vale' 
                               ,'$this->r63_difere' 
                               ,".($this->r63_dia == "null" || $this->r63_dia == ""?"null":"'".$this->r63_dia."'")." 
                               ,$this->r63_quant 
                               ,'$this->r63_obrig' 
                               ,$this->r63_quants 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dias de Vale Transporte ($this->r63_anousu."-".$this->r63_mesusu."-".$this->r63_regist."-".$this->r63_vale."-".$this->r63_difere."-".$this->r63_dia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dias de Vale Transporte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dias de Vale Transporte ($this->r63_anousu."-".$this->r63_mesusu."-".$this->r63_regist."-".$this->r63_vale."-".$this->r63_difere."-".$this->r63_dia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r63_anousu."-".$this->r63_mesusu."-".$this->r63_regist."-".$this->r63_vale."-".$this->r63_difere."-".$this->r63_dia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r63_anousu,$this->r63_mesusu,$this->r63_regist,$this->r63_vale,$this->r63_difere,$this->r63_dia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4523,'$this->r63_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4524,'$this->r63_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4525,'$this->r63_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4526,'$this->r63_vale','I')");
       $resac = db_query("insert into db_acountkey values($acount,4527,'$this->r63_difere','I')");
       $resac = db_query("insert into db_acountkey values($acount,4528,'$this->r63_dia','I')");
       $resac = db_query("insert into db_acount values($acount,599,4523,'','".AddSlashes(pg_result($resaco,0,'r63_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4524,'','".AddSlashes(pg_result($resaco,0,'r63_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4525,'','".AddSlashes(pg_result($resaco,0,'r63_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4526,'','".AddSlashes(pg_result($resaco,0,'r63_vale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4527,'','".AddSlashes(pg_result($resaco,0,'r63_difere'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4528,'','".AddSlashes(pg_result($resaco,0,'r63_dia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4529,'','".AddSlashes(pg_result($resaco,0,'r63_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4530,'','".AddSlashes(pg_result($resaco,0,'r63_obrig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,599,4531,'','".AddSlashes(pg_result($resaco,0,'r63_quants'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r63_anousu=null,$r63_mesusu=null,$r63_regist=null,$r63_vale=null,$r63_difere=null,$r63_dia=null) { 
      $this->atualizacampos();
     $sql = " update vtfdias set ";
     $virgula = "";
     if(trim($this->r63_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_anousu"])){ 
       $sql  .= $virgula." r63_anousu = $this->r63_anousu ";
       $virgula = ",";
       if(trim($this->r63_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r63_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_mesusu"])){ 
       $sql  .= $virgula." r63_mesusu = $this->r63_mesusu ";
       $virgula = ",";
       if(trim($this->r63_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r63_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_regist"])){ 
       $sql  .= $virgula." r63_regist = $this->r63_regist ";
       $virgula = ",";
       if(trim($this->r63_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r63_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_vale)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_vale"])){ 
       $sql  .= $virgula." r63_vale = '$this->r63_vale' ";
       $virgula = ",";
       if(trim($this->r63_vale) == null ){ 
         $this->erro_sql = " Campo CODIGO DO VALE TRANSPORTE nao Informado.";
         $this->erro_campo = "r63_vale";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_difere)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_difere"])){ 
       $sql  .= $virgula." r63_difere = '$this->r63_difere' ";
       $virgula = ",";
       if(trim($this->r63_difere) == null ){ 
         $this->erro_sql = " Campo Se difere cal.r922 senao r916 nao Informado.";
         $this->erro_campo = "r63_difere";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_dia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_dia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r63_dia_dia"] !="") ){ 
       $sql  .= $virgula." r63_dia = '$this->r63_dia' ";
       $virgula = ",";
       if(trim($this->r63_dia) == null ){ 
         $this->erro_sql = " Campo dia do vale transporte nao Informado.";
         $this->erro_campo = "r63_dia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r63_dia_dia"])){ 
         $sql  .= $virgula." r63_dia = null ";
         $virgula = ",";
         if(trim($this->r63_dia) == null ){ 
           $this->erro_sql = " Campo dia do vale transporte nao Informado.";
           $this->erro_campo = "r63_dia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r63_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_quant"])){ 
       $sql  .= $virgula." r63_quant = $this->r63_quant ";
       $virgula = ",";
       if(trim($this->r63_quant) == null ){ 
         $this->erro_sql = " Campo Qtda de vales do dia nao Informado.";
         $this->erro_campo = "r63_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_obrig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_obrig"])){ 
       $sql  .= $virgula." r63_obrig = '$this->r63_obrig' ";
       $virgula = ",";
       if(trim($this->r63_obrig) == null ){ 
         $this->erro_sql = " Campo informa se e obrigatorio nao Informado.";
         $this->erro_campo = "r63_obrig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r63_quants)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r63_quants"])){ 
       $sql  .= $virgula." r63_quants = $this->r63_quants ";
       $virgula = ",";
       if(trim($this->r63_quants) == null ){ 
         $this->erro_sql = " Campo qtd vales vinda da semana nao Informado.";
         $this->erro_campo = "r63_quants";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r63_anousu!=null){
       $sql .= " r63_anousu = $this->r63_anousu";
     }
     if($r63_mesusu!=null){
       $sql .= " and  r63_mesusu = $this->r63_mesusu";
     }
     if($r63_regist!=null){
       $sql .= " and  r63_regist = $this->r63_regist";
     }
     if($r63_vale!=null){
       $sql .= " and  r63_vale = '$this->r63_vale'";
     }
     if($r63_difere!=null){
       $sql .= " and  r63_difere = '$this->r63_difere'";
     }
     if($r63_dia!=null){
       $sql .= " and  r63_dia = '$this->r63_dia'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r63_anousu,$this->r63_mesusu,$this->r63_regist,$this->r63_vale,$this->r63_difere,$this->r63_dia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4523,'$this->r63_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4524,'$this->r63_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4525,'$this->r63_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4526,'$this->r63_vale','A')");
         $resac = db_query("insert into db_acountkey values($acount,4527,'$this->r63_difere','A')");
         $resac = db_query("insert into db_acountkey values($acount,4528,'$this->r63_dia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_anousu"]))
           $resac = db_query("insert into db_acount values($acount,599,4523,'".AddSlashes(pg_result($resaco,$conresaco,'r63_anousu'))."','$this->r63_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,599,4524,'".AddSlashes(pg_result($resaco,$conresaco,'r63_mesusu'))."','$this->r63_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_regist"]))
           $resac = db_query("insert into db_acount values($acount,599,4525,'".AddSlashes(pg_result($resaco,$conresaco,'r63_regist'))."','$this->r63_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_vale"]))
           $resac = db_query("insert into db_acount values($acount,599,4526,'".AddSlashes(pg_result($resaco,$conresaco,'r63_vale'))."','$this->r63_vale',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_difere"]))
           $resac = db_query("insert into db_acount values($acount,599,4527,'".AddSlashes(pg_result($resaco,$conresaco,'r63_difere'))."','$this->r63_difere',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_dia"]))
           $resac = db_query("insert into db_acount values($acount,599,4528,'".AddSlashes(pg_result($resaco,$conresaco,'r63_dia'))."','$this->r63_dia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_quant"]))
           $resac = db_query("insert into db_acount values($acount,599,4529,'".AddSlashes(pg_result($resaco,$conresaco,'r63_quant'))."','$this->r63_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_obrig"]))
           $resac = db_query("insert into db_acount values($acount,599,4530,'".AddSlashes(pg_result($resaco,$conresaco,'r63_obrig'))."','$this->r63_obrig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r63_quants"]))
           $resac = db_query("insert into db_acount values($acount,599,4531,'".AddSlashes(pg_result($resaco,$conresaco,'r63_quants'))."','$this->r63_quants',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dias de Vale Transporte nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r63_anousu."-".$this->r63_mesusu."-".$this->r63_regist."-".$this->r63_vale."-".$this->r63_difere."-".$this->r63_dia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dias de Vale Transporte nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r63_anousu."-".$this->r63_mesusu."-".$this->r63_regist."-".$this->r63_vale."-".$this->r63_difere."-".$this->r63_dia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r63_anousu."-".$this->r63_mesusu."-".$this->r63_regist."-".$this->r63_vale."-".$this->r63_difere."-".$this->r63_dia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r63_anousu=null,$r63_mesusu=null,$r63_regist=null,$r63_vale=null,$r63_difere=null,$r63_dia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r63_anousu,$r63_mesusu,$r63_regist,$r63_vale,$r63_difere,$r63_dia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4523,'$r63_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4524,'$r63_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4525,'$r63_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4526,'$r63_vale','E')");
         $resac = db_query("insert into db_acountkey values($acount,4527,'$r63_difere','E')");
         $resac = db_query("insert into db_acountkey values($acount,4528,'$r63_dia','E')");
         $resac = db_query("insert into db_acount values($acount,599,4523,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4524,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4525,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4526,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_vale'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4527,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_difere'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4528,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_dia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4529,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4530,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_obrig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,599,4531,'','".AddSlashes(pg_result($resaco,$iresaco,'r63_quants'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vtfdias
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r63_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r63_anousu = $r63_anousu ";
        }
        if($r63_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r63_mesusu = $r63_mesusu ";
        }
        if($r63_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r63_regist = $r63_regist ";
        }
        if($r63_vale != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r63_vale = '$r63_vale' ";
        }
        if($r63_difere != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r63_difere = '$r63_difere' ";
        }
        if($r63_dia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r63_dia = '$r63_dia' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dias de Vale Transporte nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r63_anousu."-".$r63_mesusu."-".$r63_regist."-".$r63_vale."-".$r63_difere."-".$r63_dia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dias de Vale Transporte nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r63_anousu."-".$r63_mesusu."-".$r63_regist."-".$r63_vale."-".$r63_difere."-".$r63_dia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r63_anousu."-".$r63_mesusu."-".$r63_regist."-".$r63_vale."-".$r63_difere."-".$r63_dia;
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
        $this->erro_sql   = "Record Vazio na Tabela:vtfdias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r63_anousu=null,$r63_mesusu=null,$r63_regist=null,$r63_vale=null,$r63_difere=null,$r63_dia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtfdias ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = vtfdias.r63_anousu and  pessoal.r01_mesusu = vtfdias.r63_mesusu and  pessoal.r01_regist = vtfdias.r63_regist";
     $sql .= "      inner join vtfempr  on  vtfempr.r16_anousu = vtfdias.r63_anousu and  vtfempr.r16_mesusu = vtfdias.r63_mesusu and  vtfempr.r16_codigo = vtfdias.r63_vale";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  on  inssirf.r33_anousu = pessoal.r01_anousu and  inssirf.r33_mesusu = pessoal.r01_mesusu and  inssirf.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pessoal.r01_anousu and  lotacao.r13_mesusu = pessoal.r01_mesusu and  lotacao.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as b on   b.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as c on   c.r37_anousu = pessoal.r01_anousu and   c.r37_mesusu = pessoal.r01_mesusu and   c.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  as d on   d.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu and   d.r37_mesusu = pessoal.r01_mesusu and   d.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu and   d.r33_mesusu = pessoal.r01_mesusu and   d.r33_codtab = pessoal.r01_tbprev";
     $sql .= "      inner join lotacao  as d on   d.r13_anousu = pessoal.r01_anousu and   d.r13_mesusu = pessoal.r01_mesusu and   d.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu and   d.r65_mesusu = pessoal.r01_mesusu and   d.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join rhempresavt  on  rhempresavt.rh35_codigo = vtfempr.r16_empres::INT";
     $sql .= "      inner join rhempresavt  as d on   d.rh35_codigo = vtfempr.r16_empres";
     $sql .= "      inner join rhempresavt  as d on   d.rh35_codigo = vtfempr.r16_empres";
     $sql2 = "";
     if($dbwhere==""){
       if($r63_anousu!=null ){
         $sql2 .= " where vtfdias.r63_anousu = $r63_anousu "; 
       } 
       if($r63_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_mesusu = $r63_mesusu "; 
       } 
       if($r63_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_regist = $r63_regist "; 
       } 
       if($r63_vale!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_vale = '$r63_vale' "; 
       } 
       if($r63_difere!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_difere = '$r63_difere' "; 
       } 
       if($r63_dia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_dia = '$r63_dia' "; 
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
   function sql_query_file ( $r63_anousu=null,$r63_mesusu=null,$r63_regist=null,$r63_vale=null,$r63_difere=null,$r63_dia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vtfdias ";
     $sql2 = "";
     if($dbwhere==""){
       if($r63_anousu!=null ){
         $sql2 .= " where vtfdias.r63_anousu = $r63_anousu "; 
       } 
       if($r63_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_mesusu = $r63_mesusu "; 
       } 
       if($r63_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_regist = $r63_regist "; 
       } 
       if($r63_vale!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_vale = '$r63_vale' "; 
       } 
       if($r63_difere!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_difere = '$r63_difere' "; 
       } 
       if($r63_dia!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " vtfdias.r63_dia = '$r63_dia' "; 
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