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

//MODULO: veiculos.off
//CLASSE DA ENTIDADE utilizac
class cl_utilizac { 
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
   var $u04_codvei = 0; 
   var $u04_datas_dia = null; 
   var $u04_datas_mes = null; 
   var $u04_datas_ano = null; 
   var $u04_datas = null; 
   var $u04_horas = null; 
   var $u04_kms = 0; 
   var $u04_destin = null; 
   var $u04_ccust = null; 
   var $u04_datad_dia = null; 
   var $u04_datad_mes = null; 
   var $u04_datad_ano = null; 
   var $u04_datad = null; 
   var $u04_horad = null; 
   var $u04_kmd = 0; 
   var $u04_login = null; 
   var $u04_dtalt_dia = null; 
   var $u04_dtalt_mes = null; 
   var $u04_dtalt_ano = null; 
   var $u04_dtalt = null; 
   var $u04_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 u04_codvei = int4 = Codigo do Veiculo 
                 u04_datas = date = Data da saida do veiculo 
                 u04_horas = char(     4) = Hora da saida do veiculo 
                 u04_kms = float8 = Km da saida do veiculo 
                 u04_destin = char(    40) = Destino do Veiculo 
                 u04_ccust = char(     4) = Centro de Custo (materiais) 
                 u04_datad = date = Data da devolucao do veiculo 
                 u04_horad = char(     4) = Hora da devolucao do veiculo 
                 u04_kmd = float8 = Km da devolucao do veiculo 
                 u04_login = char(     8) = Login do Usuario 
                 u04_dtalt = date = Data da alteracao 
                 u04_numcgm = int4 = Numero CGM 
                 ";
   //funcao construtor da classe 
   function cl_utilizac() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("utilizac"); 
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
       $this->u04_codvei = ($this->u04_codvei == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_codvei"]:$this->u04_codvei);
       if($this->u04_datas == ""){
         $this->u04_datas_dia = ($this->u04_datas_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_datas_dia"]:$this->u04_datas_dia);
         $this->u04_datas_mes = ($this->u04_datas_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_datas_mes"]:$this->u04_datas_mes);
         $this->u04_datas_ano = ($this->u04_datas_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_datas_ano"]:$this->u04_datas_ano);
         if($this->u04_datas_dia != ""){
            $this->u04_datas = $this->u04_datas_ano."-".$this->u04_datas_mes."-".$this->u04_datas_dia;
         }
       }
       $this->u04_horas = ($this->u04_horas == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_horas"]:$this->u04_horas);
       $this->u04_kms = ($this->u04_kms == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_kms"]:$this->u04_kms);
       $this->u04_destin = ($this->u04_destin == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_destin"]:$this->u04_destin);
       $this->u04_ccust = ($this->u04_ccust == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_ccust"]:$this->u04_ccust);
       if($this->u04_datad == ""){
         $this->u04_datad_dia = ($this->u04_datad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_datad_dia"]:$this->u04_datad_dia);
         $this->u04_datad_mes = ($this->u04_datad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_datad_mes"]:$this->u04_datad_mes);
         $this->u04_datad_ano = ($this->u04_datad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_datad_ano"]:$this->u04_datad_ano);
         if($this->u04_datad_dia != ""){
            $this->u04_datad = $this->u04_datad_ano."-".$this->u04_datad_mes."-".$this->u04_datad_dia;
         }
       }
       $this->u04_horad = ($this->u04_horad == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_horad"]:$this->u04_horad);
       $this->u04_kmd = ($this->u04_kmd == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_kmd"]:$this->u04_kmd);
       $this->u04_login = ($this->u04_login == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_login"]:$this->u04_login);
       if($this->u04_dtalt == ""){
         $this->u04_dtalt_dia = ($this->u04_dtalt_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_dtalt_dia"]:$this->u04_dtalt_dia);
         $this->u04_dtalt_mes = ($this->u04_dtalt_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_dtalt_mes"]:$this->u04_dtalt_mes);
         $this->u04_dtalt_ano = ($this->u04_dtalt_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_dtalt_ano"]:$this->u04_dtalt_ano);
         if($this->u04_dtalt_dia != ""){
            $this->u04_dtalt = $this->u04_dtalt_ano."-".$this->u04_dtalt_mes."-".$this->u04_dtalt_dia;
         }
       }
       $this->u04_numcgm = ($this->u04_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["u04_numcgm"]:$this->u04_numcgm);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->u04_codvei == null ){ 
       $this->erro_sql = " Campo Codigo do Veiculo nao Informado.";
       $this->erro_campo = "u04_codvei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_datas == null ){ 
       $this->erro_sql = " Campo Data da saida do veiculo nao Informado.";
       $this->erro_campo = "u04_datas_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_horas == null ){ 
       $this->erro_sql = " Campo Hora da saida do veiculo nao Informado.";
       $this->erro_campo = "u04_horas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_kms == null ){ 
       $this->erro_sql = " Campo Km da saida do veiculo nao Informado.";
       $this->erro_campo = "u04_kms";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_destin == null ){ 
       $this->erro_sql = " Campo Destino do Veiculo nao Informado.";
       $this->erro_campo = "u04_destin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_ccust == null ){ 
       $this->erro_sql = " Campo Centro de Custo (materiais) nao Informado.";
       $this->erro_campo = "u04_ccust";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_datad == null ){ 
       $this->erro_sql = " Campo Data da devolucao do veiculo nao Informado.";
       $this->erro_campo = "u04_datad_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_horad == null ){ 
       $this->erro_sql = " Campo Hora da devolucao do veiculo nao Informado.";
       $this->erro_campo = "u04_horad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_kmd == null ){ 
       $this->erro_sql = " Campo Km da devolucao do veiculo nao Informado.";
       $this->erro_campo = "u04_kmd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_login == null ){ 
       $this->erro_sql = " Campo Login do Usuario nao Informado.";
       $this->erro_campo = "u04_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_dtalt == null ){ 
       $this->erro_sql = " Campo Data da alteracao nao Informado.";
       $this->erro_campo = "u04_dtalt_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->u04_numcgm == null ){ 
       $this->erro_sql = " Campo Numero CGM nao Informado.";
       $this->erro_campo = "u04_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into utilizac(
                                       u04_codvei 
                                      ,u04_datas 
                                      ,u04_horas 
                                      ,u04_kms 
                                      ,u04_destin 
                                      ,u04_ccust 
                                      ,u04_datad 
                                      ,u04_horad 
                                      ,u04_kmd 
                                      ,u04_login 
                                      ,u04_dtalt 
                                      ,u04_numcgm 
                       )
                values (
                                $this->u04_codvei 
                               ,".($this->u04_datas == "null" || $this->u04_datas == ""?"null":"'".$this->u04_datas."'")." 
                               ,'$this->u04_horas' 
                               ,$this->u04_kms 
                               ,'$this->u04_destin' 
                               ,'$this->u04_ccust' 
                               ,".($this->u04_datad == "null" || $this->u04_datad == ""?"null":"'".$this->u04_datad."'")." 
                               ,'$this->u04_horad' 
                               ,$this->u04_kmd 
                               ,'$this->u04_login' 
                               ,".($this->u04_dtalt == "null" || $this->u04_dtalt == ""?"null":"'".$this->u04_dtalt."'")." 
                               ,$this->u04_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de controle de utilizacao de veiculos e maq () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de controle de utilizacao de veiculos e maq já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de controle de utilizacao de veiculos e maq () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update utilizac set ";
     $virgula = "";
     if(trim($this->u04_codvei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_codvei"])){ 
       $sql  .= $virgula." u04_codvei = $this->u04_codvei ";
       $virgula = ",";
       if(trim($this->u04_codvei) == null ){ 
         $this->erro_sql = " Campo Codigo do Veiculo nao Informado.";
         $this->erro_campo = "u04_codvei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_datas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_datas_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["u04_datas_dia"] !="") ){ 
       $sql  .= $virgula." u04_datas = '$this->u04_datas' ";
       $virgula = ",";
       if(trim($this->u04_datas) == null ){ 
         $this->erro_sql = " Campo Data da saida do veiculo nao Informado.";
         $this->erro_campo = "u04_datas_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["u04_datas_dia"])){ 
         $sql  .= $virgula." u04_datas = null ";
         $virgula = ",";
         if(trim($this->u04_datas) == null ){ 
           $this->erro_sql = " Campo Data da saida do veiculo nao Informado.";
           $this->erro_campo = "u04_datas_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->u04_horas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_horas"])){ 
       $sql  .= $virgula." u04_horas = '$this->u04_horas' ";
       $virgula = ",";
       if(trim($this->u04_horas) == null ){ 
         $this->erro_sql = " Campo Hora da saida do veiculo nao Informado.";
         $this->erro_campo = "u04_horas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_kms)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_kms"])){ 
       $sql  .= $virgula." u04_kms = $this->u04_kms ";
       $virgula = ",";
       if(trim($this->u04_kms) == null ){ 
         $this->erro_sql = " Campo Km da saida do veiculo nao Informado.";
         $this->erro_campo = "u04_kms";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_destin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_destin"])){ 
       $sql  .= $virgula." u04_destin = '$this->u04_destin' ";
       $virgula = ",";
       if(trim($this->u04_destin) == null ){ 
         $this->erro_sql = " Campo Destino do Veiculo nao Informado.";
         $this->erro_campo = "u04_destin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_ccust)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_ccust"])){ 
       $sql  .= $virgula." u04_ccust = '$this->u04_ccust' ";
       $virgula = ",";
       if(trim($this->u04_ccust) == null ){ 
         $this->erro_sql = " Campo Centro de Custo (materiais) nao Informado.";
         $this->erro_campo = "u04_ccust";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_datad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_datad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["u04_datad_dia"] !="") ){ 
       $sql  .= $virgula." u04_datad = '$this->u04_datad' ";
       $virgula = ",";
       if(trim($this->u04_datad) == null ){ 
         $this->erro_sql = " Campo Data da devolucao do veiculo nao Informado.";
         $this->erro_campo = "u04_datad_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["u04_datad_dia"])){ 
         $sql  .= $virgula." u04_datad = null ";
         $virgula = ",";
         if(trim($this->u04_datad) == null ){ 
           $this->erro_sql = " Campo Data da devolucao do veiculo nao Informado.";
           $this->erro_campo = "u04_datad_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->u04_horad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_horad"])){ 
       $sql  .= $virgula." u04_horad = '$this->u04_horad' ";
       $virgula = ",";
       if(trim($this->u04_horad) == null ){ 
         $this->erro_sql = " Campo Hora da devolucao do veiculo nao Informado.";
         $this->erro_campo = "u04_horad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_kmd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_kmd"])){ 
       $sql  .= $virgula." u04_kmd = $this->u04_kmd ";
       $virgula = ",";
       if(trim($this->u04_kmd) == null ){ 
         $this->erro_sql = " Campo Km da devolucao do veiculo nao Informado.";
         $this->erro_campo = "u04_kmd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_login"])){ 
       $sql  .= $virgula." u04_login = '$this->u04_login' ";
       $virgula = ",";
       if(trim($this->u04_login) == null ){ 
         $this->erro_sql = " Campo Login do Usuario nao Informado.";
         $this->erro_campo = "u04_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->u04_dtalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_dtalt_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["u04_dtalt_dia"] !="") ){ 
       $sql  .= $virgula." u04_dtalt = '$this->u04_dtalt' ";
       $virgula = ",";
       if(trim($this->u04_dtalt) == null ){ 
         $this->erro_sql = " Campo Data da alteracao nao Informado.";
         $this->erro_campo = "u04_dtalt_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["u04_dtalt_dia"])){ 
         $sql  .= $virgula." u04_dtalt = null ";
         $virgula = ",";
         if(trim($this->u04_dtalt) == null ){ 
           $this->erro_sql = " Campo Data da alteracao nao Informado.";
           $this->erro_campo = "u04_dtalt_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->u04_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["u04_numcgm"])){ 
       $sql  .= $virgula." u04_numcgm = $this->u04_numcgm ";
       $virgula = ",";
       if(trim($this->u04_numcgm) == null ){ 
         $this->erro_sql = " Campo Numero CGM nao Informado.";
         $this->erro_campo = "u04_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de controle de utilizacao de veiculos e maq nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de controle de utilizacao de veiculos e maq nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from utilizac
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de controle de utilizacao de veiculos e maq nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de controle de utilizacao de veiculos e maq nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:utilizac";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>